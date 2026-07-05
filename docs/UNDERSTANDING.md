## What is the task asking me to build?

This task is asking me to build a small Laravel API for coaches to manage and view their client roster.

A coach should be able to:

                        - View a paginated list of their own clients.
                        - Search clients by name or email.
                        - Filter clients by subscription status.
                        - Sort clients by name, joined date, or engagement.
                        - View a single client.
                        - Update a client’s subscription status.
                        - View aggregate roster stats.

The most important rule is that a coach must only ever see or modify their own clients. No endpoint should expose another coach’s clients.

The previous developer claimed that passing the request value directly into Eloquent orderBy() is safe because Laravel handles column name safety automatically.

The previous developer’s claim is incorrect. User-controlled sort fields should not be passed directly into orderBy(). Even though Laravel may quote identifiers for the database driver, it does not mean arbitrary user input is safe or valid as a column name. Passing request input directly into orderBy() can create SQL injection risk, query errors, or allow users to sort by fields they should not be able to use, such as password. The safe approach is to whitelist allowed sort keys and map them to known database columns.

---------------------------------------------------------------------------------------------------------------------------------------------------

## What inputs does it take?

The API uses authenticated requests with Laravel Sanctum.

All endpoints require:
                        A logged-in user.
                        The user must have role coach.

The main roster endpoint is:

GET /api/clients

--------------------------------------------------------------------

## search

Searches client name or email using a partial, case-insensitive match.

--------------------------------------------------------------------

## status

Filters by client subscription status.

--------------------------------------------------------------------

## sort

Allowed sort values:

name
joined_at
engagement

--------------------------------------------------------------------

## direction

Allowed directions:

asc
desc

Default direction is asc.

--------------------------------------------------------------------

## page

Uses standard Laravel pagination with 20 clients per page.

---------------------------------------------------------------------------------------------------------------------------------------------------

## What does it return?

GET /api/clients

Returns a paginated list of the authenticated coach’s clients.

The response should include standard Laravel pagination metadata, including the total.

-------------------------------------------------------

GET /api/clients/{id}

Returns a single client if the authenticated coach owns that client.

If the client belongs to another coach, the endpoint should return 403, not 404.

-------------------------------------------------------

PATCH /api/clients/{id}/status

Updates the subscription status of a client if the authenticated coach owns that client.

-------------------------------------------------------

GET /api/clients/stats

Returns aggregate stats for the authenticated coach’s own roster only.

---------------------------------------------------------------------------------------------------------------------------------------------------

## Data model

The project needs a clients table.

Columns:

        - id
        - coach_id
        - name
        - email
        - status
        - joined_at
        - last_activity_at
        - created_at
        - updated_at

The status field supports:

active
cancelled
past_due

The last_activity_at field is nullable.

---------------------------------------------------------------------------------------------------------------------------------------------------

## Evaluation of the previous developer’s orderBy() claim

The previous developer said:

<Laravel Eloquent's orderBy() handles column name safety automatically, so we just pass $request->sort directly into orderBy().>

I believe this is incorrect and unsafe.

Laravel does not turn arbitrary request input into a safe business-approved sort field. Even if Laravel quotes identifiers for the SQL grammar, user-controlled column names should not be passed directly into queries.

The risks are:

                - SQL injection risk if raw expressions or unsafe input reach the query.
                - Query errors if the user passes a non-existent column.
                - Data exposure through sorting by sensitive or unintended columns.
                - Broken product behaviour, for example ?sort=password.
                - No clear mapping for product-level sort names like engagement.

The safe approach is to use a whitelist.

If a request sends:

GET /api/clients?sort=password

the endpoint should ignore password and default to:

sort=name
direction=asc

This decision happens in the controller before calling orderBy().

---------------------------------------------------------------------------------------------------------------------------------------------------

## What does sort=engagement map to?

sort=engagement maps to the last_activity_at column.

This is a product decision.

The brief says engagement means “most recently active first.” The closest data field for that is last_activity_at, because it records when the client was last active.

So:

GET /api/clients?sort=engagement

will sort by:

last_activity_at desc

Even if the request sends direction=asc, I will treat engagement as “most recently active first” because the acceptance criteria specifically says sort=engagement sorts by most recently active first.

---------------------------------------------------------------------------------------------------------------------------------------------------

## What happens with search=%?

The search feature uses partial matching with SQL LIKE.

A search value of % is special because % is a SQL wildcard. If passed directly into a LIKE query as:

LIKE '%%%'

it would match almost everything.

That means ?search=% would effectively behave like no search filter.

This is not a cross-coach data leak as long as the coach scope is always applied first. However, it could make the search behave in a surprising way.

My decision is:

                - Trim the search input.
                - If the search is empty after trimming, ignore it.
                - Escape SQL wildcard characters like % and _ so the search behaves like a literal text search.
                - Keep the coach scope applied regardless of the search value.

So ?search=% will search for a literal % character instead of turning into “match all”.

If there are no clients with % in their name or email, it should return no matching clients for that coach.

---------------------------------------------------------------------------------------------------------------------------------------------------

## How I will prevent one coach from accessing another coach’s clients

Every client query must be scoped by the authenticated coach’s user ID.

For list and stats endpoints, I will start queries like:

Client::query()
    ->where('coach_id', $request->user()->id)

Then search, filters, sorting, and pagination are applied after that.

For viewing or updating a single client, I will still load the client by ID, but then explicitly check ownership.

---------------------------------------------------------------------------------------------------------------------------------------------------

## sort=engagement is not a real database column

The API exposes engagement as a sort option, but the database table has no engagement column.

Engagement means last_activity_at, because the acceptance criteria says it should sort by most recently active first

---------------------------------------------------------------------------------------------------------------------------------------------------

## Direction handling for engagement

sort=engagement should force desc regardless of the requested direction, because that matches the product meaning.

The query parameter supports direction=asc or direction=desc, but the acceptance criteria says sort=engagement means “most recently active first.”

Most recently active first means descending by last_activity_at.

---------------------------------------------------------------------------------------------------------------------------------------------------

## Invalid status should be ignored

Usually invalid filters might return validation errors, but the acceptance criteria explicitly says invalid status values are ignored with no 422.

So if the user sends:

GET /api/clients?status=paused

I will return the normal coach-scoped roster without applying a status filter.

---------------------------------------------------------------------------------------------------------------------------------------------------

## Invalid sort should default to name ascending

The brief says unrecognised sort values default to name ascending.

So if the user sends:

GET /api/clients?sort=email

or:

GET /api/clients?sort=password

---------------------------------------------------------------------------------------------------------------------------------------------------

## GET /api/clients/stats route could conflict with GET /api/clients/{id}

The route /api/clients/stats could be accidentally captured by /api/clients/{id} if the route order is wrong.

I will define the stats route before the {id} route.

---------------------------------------------------------------------------------------------------------------------------------------------------

## Case-insensitive search depends on database behaviour

SQLite LIKE is generally case-insensitive for ASCII by default, but behaviour can differ between databases and collations.

To make the intent clear, I will use a lower-case comparison approach where practical, such as:

LOWER(name) LIKE ?
OR LOWER(email) LIKE ?

with the lowered search input.

---------------------------------------------------------------------------------------------------------------------------------------------------

## Nullable last_activity_at sorting

Some clients may have last_activity_at = null

When sorting by engagement, clients with a recent last_activity_at should appear first, and clients with null activity should appear later.

---------------------------------------------------------------------------------------------------------------------------------------------------

## Role handling on the users table

The brief says users have a role column, but a fresh Laravel project does not include this column.

I will add a migration to include role on users.

---------------------------------------------------------------------------------------------------------------------------------------------------

## Seeder requirements differ slightly

The acceptance criteria says seed two coaches:

Coach A with 5 clients
Coach B with 3 clients

The deliverables say the seeder should include at least 2 coaches and 5+ clients each.

I will follow the stricter deliverable and seed at least 5 clients for each coach. In tests, I can still create the exact isolation scenario of Coach A with 5 clients and Coach B with 3 clients.