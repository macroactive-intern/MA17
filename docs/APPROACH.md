Overview

I am building a Laravel API that lets authenticated coaches manage their client roster.

The API will support:

Listing clients with pagination.
Searching by client name or email.
Filtering by subscription status.
Sorting by approved sort options only.
Viewing a single client.
Updating a client’s subscription status.
Viewing aggregate stats for the authenticated coach’s roster.

The most important implementation rule is coach isolation. Every query must be scoped to the authenticated coach so one coach can never see or update another coach’s clients.

I will also fix the sorting risk from the previous developer’s note. I will not pass request input directly into orderBy(). Instead, I will use a whitelist of allowed sort keys and map those keys to real database columns.

Data Model
Existing users table update

A fresh Laravel project does not include a role column on users, but the brief requires users to have either a coach or client role.

I will add a migration to add:

role

Allowed values:

coach
client

I will use this field to block non-coach users from accessing the roster endpoints.

New clients table

I will create a clients table with the columns required by the brief.

id
coach_id
name
email
status
joined_at
last_activity_at
created_at
updated_at
Column details
id

Primary key.

coach_id

Foreign key to users.id.

This identifies which coach owns the client.

name

String, max length 100.

email

String, max length 150.

status

Subscription status.

Allowed values:

active
cancelled
past_due
joined_at

Timestamp showing when the client joined.

last_activity_at

Nullable timestamp showing when the client was last active.

This will be used for the engagement sort.

Database Constraints and Indexes

I will add a foreign key constraint:

clients.coach_id -> users.id

I will also add indexes for fields used in filtering and sorting:

coach_id
status
joined_at
last_activity_at

I will also consider a combined index:

coach_id, status

The endpoint must support coaches with hundreds of clients, so the query should avoid loading all rows into PHP and should let the database handle filtering, sorting, and pagination.

Models
Client model

The Client model will include fillable fields:

protected $fillable = [
    'coach_id',
    'name',
    'email',
    'status',
    'joined_at',
    'last_activity_at',
];

It will cast dates:

protected $casts = [
    'joined_at' => 'datetime',
    'last_activity_at' => 'datetime',
];

It will have a relationship back to the coach:

public function coach()
{
    return $this->belongsTo(User::class, 'coach_id');
}
User model

The User model will have a relationship for coach-owned clients:

public function clients()
{
    return $this->hasMany(Client::class, 'coach_id');
}
Endpoints and Routes

All routes will be protected with:

auth:sanctum

I will also add coach-only access checking, either through middleware or controller-level checks.

Routes:

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/clients/stats', [ClientController::class, 'stats']);
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/clients/{client}', [ClientController::class, 'show']);
    Route::patch('/clients/{client}/status', [ClientController::class, 'updateStatus']);
});

The /clients/stats route must be defined before /clients/{client} so Laravel does not treat stats as a route parameter.

Coach-Only Access

All endpoints are only for authenticated users with role:

coach

Expected behaviour:

Unauthenticated user -> 401
Authenticated client user -> 403
Authenticated coach user -> allowed

I will add a helper method in the controller or a middleware such as:

private function ensureCoach(Request $request): void
{
    if ($request->user()->role !== 'coach') {
        abort(403);
    }
}

This check will run before roster logic.

Coach Scope Enforcement

Every roster query must be scoped by the authenticated coach’s ID.

For list and stats endpoints, the base query will always start like this:

$query = Client::query()
    ->where('coach_id', $request->user()->id);

Search, filters, sorting, and pagination will be applied after this coach scope.

This prevents requests like:

GET /api/clients?search=alice

from returning another coach’s client named Alice.

For single-client endpoints, I will check ownership after route model binding loads the client:

if ($client->coach_id !== $request->user()->id) {
    abort(403);
}

This is required because the acceptance criteria says cross-coach access must return 403, not 404.

GET /api/clients Filter Chain Design

The roster list endpoint will build one database query in this order:

Start with authenticated coach scope.
Apply optional search.
Apply optional status filter.
Apply safe sort mapping.
Paginate with 20 results per page.

Example structure:

$query = Client::query()
    ->where('coach_id', $request->user()->id);

$this->applySearch($query, $request);
$this->applyStatusFilter($query, $request);
$this->applySort($query, $request);

return $query->paginate(20);
Search Design

The search query parameter filters by client name OR email.

Example:

GET /api/clients?search=alice

The query should match:

name contains alice
OR email contains alice

The search must be case-insensitive.

I will lower both the database field and the search term:

$search = strtolower(trim($request->query('search', '')));

Then apply:

$query->where(function ($inner) use ($search) {
    $inner->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
          ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"]);
});

The search condition must be wrapped in a closure so the OR does not break coach scoping.

Correct:

where coach_id = current coach
and (
    name matches search
    or email matches search
)

Incorrect:

where coach_id = current coach
and name matches search
or email matches search

The incorrect version could leak another coach’s clients if the email condition matches outside the coach scope.

Handling search=%

% and _ have special meaning in SQL LIKE.

If the user searches:

GET /api/clients?search=%

and I pass that directly into a LIKE pattern, it can match everything.

This does not become a cross-coach leak if coach scoping is correct, but it still makes search behave unexpectedly.

My decision is to escape SQL wildcard characters in search input.

Characters to escape:

%
_
\

So search=% will be treated as a literal percent sign, not “match all”.

If no client has % in their name or email, the response should return no matching clients for that coach.

Status Filter Design

The status query parameter supports:

active
cancelled
past_due

Example:

GET /api/clients?status=active

If the status is valid, I will apply:

$query->where('status', $status);

If the status is invalid, I will ignore it and continue without a status filter.

Example:

GET /api/clients?status=paused

This should not return 422.

It should behave like no status filter was provided.

Sort Whitelist Approach

I will not pass request input directly into orderBy().

The previous developer’s claim is incorrect because user-controlled values should not be treated as trusted column names.

Unsafe approach:

$query->orderBy($request->query('sort'), $request->query('direction'));

Safe approach:

$allowedSorts = [
    'name' => 'name',
    'joined_at' => 'joined_at',
    'engagement' => 'last_activity_at',
];

Only keys from this whitelist are allowed.

Sorting Rules

Allowed sort values:

name
joined_at
engagement

Allowed directions:

asc
desc

Default direction:

asc

Default sort:

name asc

If the user sends an unknown sort value, such as:

GET /api/clients?sort=password

the endpoint will ignore it and return results sorted by:

name asc

This decision happens before orderBy() is called.

Example logic:

$sort = $request->query('sort', 'name');
$direction = $request->query('direction', 'asc');

$allowedSorts = [
    'name' => 'name',
    'joined_at' => 'joined_at',
    'engagement' => 'last_activity_at',
];

if (! array_key_exists($sort, $allowedSorts)) {
    $sort = 'name';
    $direction = 'asc';
}

if (! in_array($direction, ['asc', 'desc'], true)) {
    $direction = 'asc';
}

$query->orderBy($allowedSorts[$sort], $direction);
Evaluation of orderBy() Safety

The previous developer said Laravel Eloquent’s orderBy() handles column name safety automatically.

I do not consider that safe enough.

Even if Laravel quotes identifiers, it does not mean arbitrary user input is safe as a business-level sort field.

Risks include:

SQL injection risk if unsafe input reaches raw SQL.
Query errors from invalid column names.
Users sorting by fields the API should not expose.
Users attempting fields like password, remember_token, or internal columns.
Product sort keys like engagement not mapping to real database columns.

The safe approach is to whitelist allowed public sort keys and map those keys to known database columns.

sort=engagement Mapping

The brief says:

sort=engagement sorts by most recently active first

There is no engagement column in the clients table.

I will map:

engagement -> last_activity_at

This is a product decision because engagement is a user-facing concept, not a database column.

I will sort engagement by:

last_activity_at desc

because “most recently active first” means newest activity timestamp first.

So this request:

GET /api/clients?sort=engagement

will behave like:

$query->orderBy('last_activity_at', 'desc');

I will force desc for engagement because the acceptance criteria defines engagement as “most recently active first”.

I cannot pass engagement directly to orderBy() because there is no engagement column.

Pagination

The roster endpoint will use standard Laravel pagination:

$query->paginate(20);

The response should include standard pagination metadata.

The acceptance criteria specifically checks the metadata total.

If Coach A has 5 clients and Coach B has 3 clients, then:

GET /api/clients

as Coach A should return pagination metadata with:

{
  "total": 5
}

not:

{
  "total": 8
}
GET /api/clients/{id} Design

This endpoint returns one client.

Code path:

Require authenticated Sanctum user.
Confirm user role is coach.
Load the client by route model binding.
Compare client.coach_id with authenticated user ID.
If they do not match, return 403.
If they match, return the client.

Example:

public function show(Request $request, Client $client)
{
    $this->ensureCoach($request);

    if ($client->coach_id !== $request->user()->id) {
        abort(403);
    }

    return response()->json($client);
}

A missing client ID can still return Laravel’s normal 404.

A real client that belongs to another coach must return 403.

PATCH /api/clients/{id}/status Design

This endpoint updates a client’s subscription status.

Code path:

Require authenticated Sanctum user.
Confirm user role is coach.
Load the client.
Check ownership.
Validate the new status.
Update only the status field.
Return the updated client.

Valid status values:

active
cancelled
past_due

Unlike the list filter, invalid update status should not be ignored. For an update, invalid input should return a validation error because the API is being asked to change stored data.

Example validation:

$request->validate([
    'status' => ['required', Rule::in(['active', 'cancelled', 'past_due'])],
]);
GET /api/clients/stats Design

This endpoint returns aggregate stats for the authenticated coach’s own roster only.

The query must start with:

Client::where('coach_id', $request->user()->id)

Response fields:

total
active
cancelled
past_due
newest_joined_at

Implementation approach:

$baseQuery = Client::query()
    ->where('coach_id', $request->user()->id);

return [
    'total' => (clone $baseQuery)->count(),
    'active' => (clone $baseQuery)->where('status', 'active')->count(),
    'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
    'past_due' => (clone $baseQuery)->where('status', 'past_due')->count(),
    'newest_joined_at' => (clone $baseQuery)->max('joined_at'),
];

If the coach has no clients:

{
  "total": 0,
  "active": 0,
  "cancelled": 0,
  "past_due": 0,
  "newest_joined_at": null
}
Seeder Plan

The seeder will create:

Coach A.
Coach B.
At least 5 clients for Coach A.
At least 5 clients for Coach B.

Clients will have varied:

Names.
Emails.
Statuses.
Joined dates.
Last activity dates.

This will make it easy to manually test:

Search.
Status filtering.
Name sorting.
Joined date sorting.
Engagement sorting.
Coach isolation.

The acceptance criteria has a test scenario with Coach A having 5 clients and Coach B having 3 clients. I will create that exact shape inside the feature tests even if the seeder has 5+ clients for both coaches.

Testing Approach

I will write feature tests around the required behaviours.

Auth and role tests
Unauthenticated user gets 401.
Authenticated user with role client gets 403.
Authenticated coach can access roster endpoints.
Roster list tests
No query parameters returns the coach’s full paginated roster.
Pagination metadata total is scoped to the authenticated coach.
Search matches name.
Search matches email.
Search is case-insensitive.
Search never returns another coach’s matching client.
search=% does not match every client.
Valid status filters correctly.
Invalid status is ignored.
Results are paginated 20 per page.
Sort tests
sort=name&direction=asc works.
sort=name&direction=desc works.
sort=joined_at&direction=asc works.
sort=joined_at&direction=desc works.
sort=engagement sorts by last_activity_at desc.
sort=password defaults to name asc.
Invalid direction defaults to asc.
Isolation tests
Coach A has 5 clients and Coach B has 3 clients.
Coach A list response has pagination total 5, not 8.
Coach A trying to view Coach B’s client gets 403.
Coach A trying to update Coach B’s client status gets 403.
Stats tests
Total count only includes authenticated coach’s clients.
Active count only includes authenticated coach’s clients.
Cancelled count only includes authenticated coach’s clients.
Past due count only includes authenticated coach’s clients.
Newest joined date only considers authenticated coach’s clients.
Status update tests
Coach can update their own client’s status.
Coach cannot update another coach’s client.
Invalid status update fails validation.
Libraries and Packages
Laravel

Used as the required framework for the API.

Laravel Sanctum

Used for token-based API authentication because the brief requires:

auth:sanctum
SQLite

Used for local development and testing because the project setup requires SQLite configuration.

PHPUnit / Laravel Feature Tests

Used for automated endpoint tests.

No extra package is needed for filtering, sorting, or authorization because the feature is small enough to implement clearly in the controller with Laravel’s built-in tools.

Edge Cases
Invalid sort value

Example:

GET /api/clients?sort=password

The API will return the normal roster sorted by:

name asc

This prevents unsafe or unintended fields from being used.

Invalid direction value

Example:

GET /api/clients?direction=sideways

The API will default to:

asc
Invalid status filter

Example:

GET /api/clients?status=paused

The API will ignore the status filter and still return 200.

Invalid status update

Example:

{
  "status": "paused"
}

The API will return a validation error because updating stored data requires valid input.

Search wildcard characters

Example:

GET /api/clients?search=%

The % will be escaped and treated as a literal search character.

Empty search

Example:

GET /api/clients?search=

The search filter will be ignored.

Cross-coach search

Example:

GET /api/clients?search=alice

If another coach has a client named Alice, that client must not appear.

Cross-coach show

Example:

GET /api/clients/5

If client 5 belongs to another coach, the API returns 403.

Cross-coach status update

Example:

PATCH /api/clients/5/status

If client 5 belongs to another coach, the API returns 403.

Stats leakage

Stats must only count the authenticated coach’s clients. Other coaches’ clients must not affect totals.

Nullable last_activity_at

When sorting by engagement, clients with real recent activity should appear before clients with null activity.

If database null ordering becomes inconsistent, I will explicitly handle nulls so null activity appears last.

Route conflict with stats

/api/clients/stats must be registered before /api/clients/{client}.

Decisions from Ambiguities
Decision: direct orderBy($request->sort) is unsafe

I will not use request input directly as a database column.

I will use a whitelist.

Decision: engagement means last_activity_at desc

The brief says engagement means most recently active first.

Therefore:

engagement = last_activity_at desc
Decision: invalid status filter is ignored

The acceptance criteria explicitly says invalid status filters should not return 422.

Decision: invalid status update is rejected

Filtering and updating are different.

Invalid filter values are ignored, but invalid update values should fail validation.

Decision: cross-coach single-client access returns 403

The acceptance criteria explicitly says this should be 403, not 404.

Decision: search=% is escaped

This keeps search behaviour literal and avoids wildcard match-all behaviour.

Decision: stats route comes before show route

This avoids Laravel treating stats as a client ID.

Final Implementation Shape

The main controller will likely contain methods like:

index(Request $request)
stats(Request $request)
show(Request $request, Client $client)
updateStatus(Request $request, Client $client)

Helper methods may include:

ensureCoach(Request $request)
ensureOwnsClient(Request $request, Client $client)
applySearch(Builder $query, Request $request)
applyStatusFilter(Builder $query, Request $request)
applySort(Builder $query, Request $request)
escapeLike(string $value)

This keeps the controller readable and makes the security-sensitive decisions easy to find during review.