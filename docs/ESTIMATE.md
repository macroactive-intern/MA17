Step 1

    Project set up
                1. Start new Laravel project
                2. connect to Github repo
                                                                                                    10 mins

----------------------------------------------------------------------------------------------------------------

Step 2

    Documentation
                1. Write out the Understand.md
                2. Write out the Time Estimate.md
                3. Add the Ai Time estimate to the Estimate.md
                4. Write out the Aproach.md
                                                                                                        120 mins

----------------------------------------------------------------------------------------------------------------

Step 3

    Finish Project set up
                1. Install dependencies
                2. Install Sanctum
                3. Install Pest
                4. Confirm API/auth setup
                                                                                                    20 mins

----------------------------------------------------------------------------------------------------------------

Step 4

    Feature Tests
                1. Authentication tests
                                    - Unauthenticated user cannot access endpoints.
                                    - Client-role user gets 403.
                                    - Coach-role user can access own roster.
                
                2. Roster list tests
                                    - No query parameters returns paginated own clients.
                                    - Pagination metadata total is coach-scoped.
                                    - Search filters by name.
                                    - Search filters by email.
                                    - Search is case-insensitive.
                                    - Search does not return another coach’s matching client.
                                    - search=% does not accidentally match everything.
                                    - Valid status filters correctly.
                                    - Invalid status is ignored.

                3. Sort tests
                                    - sort=name&direction=asc works.
                                    - sort=name&direction=desc works.
                                    - sort=joined_at&direction=asc works.
                                    - sort=joined_at&direction=desc works.
                                    - sort=engagement sorts by last_activity_at desc.
                                    - sort=password defaults to name asc.
                                    - Invalid direction defaults safely. 

                4. Isolation tests
                                    - Coach A with 5 clients and Coach B with 3 clients.
                                    - Coach A list response has pagination total: 5, not 8.
                                    - Coach A cannot view Coach B’s client.
                                    - Cross-coach show returns 403, not 404.
                                    - Coach A cannot update Coach B’s client status.     

                5. Stats tests
                                    - Counts only authenticated coach’s clients.
                                    - Active count is correct.
                                    - Cancelled count is correct.
                                    - Past due count is correct.
                                    - Newest joined date is correct.
                                    - Other coach’s clients do not affect stats.

                6. Status update tests
                                    - Coach can update own client status.
                                    - Invalid status update returns validation error.
                                    - Cross-coach update returns 403.
                                                                                                                         120 mins

---------------------------------------------------------------------------------------------------------------------------------------------------

Step 5

    Database and Models
                
                1. Add role column to users table
                                                    Create migration for role.
                                                    Allow coach and client.
                                                    Set sensible default if needed.
                2. Create clients migration
                                            id
                                            coach_id
                                            name
                                            email
                                            status
                                            joined_at
                                            last_activity_at
                                            timestamps                  
                
                3. Add database constraints/indexes
                                            Foreign key from clients.coach_id to users.id.
                                            Index coach_id.
                                            Index status.
                                            Index joined_at.
                                            Index last_activity_at.
                                            Consider index for coach_id + status.
                
                4. Create Client model
                                            Add fillable fields.
                                            Add casts for timestamps.
                                            Add relationship to User.
                
                5. Update User model
                                            Add role fillable/cast if needed.
                                            Add clients() relationship for coaches.
                                                                                                              45 mins

----------------------------------------------------------------------------------------------------------------

Step 6

    Routes and Controller

                1. Create controller
                2. Register API routes
                3. Apply middleware
                4. Ensure route order
                                                                                                    20 mins

----------------------------------------------------------------------------------------------------------------

Step 7

    Auth and Role Protection

                1. Add coach-only access logic
                                            Middleware or controller guard.
                                            Non-authenticated users get 401.
                                            Authenticated non-coach users get 403.
                
                2. Add ownership checks
                                        List endpoint only queries authenticated coach’s clients.
                                        Stats endpoint only queries authenticated coach’s clients.
                                        Show endpoint returns 403 for another coach’s client.
                                        Status update endpoint returns 403 for another coach’s client.

                                                                                                    45 mins

----------------------------------------------------------------------------------------------------------------

Step 8

    Roster List Endpoint

                1. Build base query
                                    Start with Client::where('coach_id', auth()->id()).
                
                2. Add search filter
                                    Search by name OR email.
                                    Case-insensitive partial match.
                                    Escape % and _.
                                    Ignore empty search.
                
                3. Add status filter
                                    Accept active, cancelled, past_due.
                                    Ignore invalid status values.
                
                4. Add sort whitelist
                                    name => name
                                    joined_at => joined_at
                                    engagement => last_activity_at
                
                5. Add direction handling
                                    Accept asc or desc.
                                    Default to asc.
                                    Invalid direction defaults to asc.
                                    Invalid sort defaults to name asc.
                
                6. Add engagement sort behaviour
                                    Sort by last_activity_at desc.
                                    Put most recently active clients first.
                
                7. Add pagination
                                    20 per page.
                                    Return standard Laravel pagination metadata.
                                                                                                    35 mins

----------------------------------------------------------------------------------------------------------------

Step 9

    Single Client Endpoint

                1. Implement GET /api/clients/{id}
                                                    Load client by ID.
                                                    Check ownership.
                                                    Return 403 if another coach owns it.
                                                    Return client data if owned.
                
                2. Decide missing client behaviour
                                                    Missing ID can return normal 404.
                                                                                                    20 mins

----------------------------------------------------------------------------------------------------------------

Step 10 

    Status Update Endpoint

                1. Implement PATCH /api/clients/{id}/status
                                                            Load client by ID.
                                                            Check ownership.
                                                            Return 403 if another coach owns it.

                2. Validate request body
                                        status is required.
                                        Must be one of active, cancelled, past_due.
                
                3. Update status
                                Save client.
                                Return updated client data.
                                                                                                    30 mins

----------------------------------------------------------------------------------------------------------------

Step 11

    Stats Endpoint

                1. Implement GET /api/clients/stats
                                                Scope by authenticated coach ID.
                
                2. Return counts
                                total
                                active
                                cancelled
                                past_due
                
                3. Return newest join date
                                newest_joined_at
                                Based only on authenticated coach’s clients

                4. Handle empty roster
                                Counts should be 0.
                                newest_joined_at can be null. 
                                                                                                    25 mins

----------------------------------------------------------------------------------------------------------------

Step 12

    Seeder

                1. Create seeder data
                                    Coach A.
                                    Coach B.
                                    At least 5 clients for Coach A.
                                    At least 5 clients for Coach B.
                
                2. Include varied client data
                                    Different names.
                                    Different emails.
                                    Different statuses.
                                    Different joined_at dates.
                                    Different last_activity_at dates.
                                    Some nullable last_activity_at.

                3. Confirm seeded data
                                    Run migrations and seed.
                                    Check clients are assigned to correct coaches.
                                                                                                    35 mins

----------------------------------------------------------------------------------------------------------------

Step 13

    Run Tests
                                                                                                    20 mins

----------------------------------------------------------------------------------------------------------------

Step 14

    Fix any failing tests
                                                                                                    40 mins

----------------------------------------------------------------------------------------------------------------

Step 15

    Manual test
                                                                                                    45 mins

----------------------------------------------------------------------------------------------------------------

Step 16 

    BEFORE-AFTER.md
                                                                                                    30 mins
----------------------------------------------------------------------------------------------------------------

                                                                                                    11 hrs

---------------------------------------------------------------------------------------------------------------- 

AI Estimate

Estimated total: 12 hrs

This task is larger than a simple CRUD API because the endpoint needs coach-scoped access, filtering, searching, safe sorting, pagination metadata, status updates, stats, seed data, and a wide feature test suite.

The highest-risk areas are:

Making sure every query is scoped to the authenticated coach.
Ensuring pagination totals do not include another coach’s clients.
Escaping % and _ in search so search=% does not accidentally match everything.
Safely handling sort fields with a whitelist instead of passing request input directly into orderBy().
Returning 403 for cross-coach access instead of accidentally returning another coach’s data.
Debugging feature tests across filtering, sorting, stats, and authorization.
AI Breakdown
Step	Estimate
Project setup	15 mins
Documentation	110 mins
Laravel/Sanctum/Pest setup	30 mins
Feature tests	150 mins
Database, models, relationships, indexes	60 mins
Routes and controller setup	30 mins
Auth and ownership protection	55 mins
Roster list endpoint	70 mins
Single client endpoint	25 mins
Status update endpoint	35 mins
Stats endpoint	35 mins
Seeder	40 mins
Run tests	20 mins
Fix failing tests	75 mins
Manual testing	50 mins
BEFORE-AFTER.md	35 mins
Total

835 mins — about 13 hrs 55 mins with buffer

A more realistic working estimate is 12 hrs. The safe quoted estimate is 13 hrs because filtering, sorting, pagination, and cross-coach isolation can take extra time to test and debug properly.