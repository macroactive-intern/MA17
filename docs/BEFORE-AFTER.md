 PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   FAIL  Tests\Feature\AuthTest
  ⨯ it returns 401 when unauthenticated accessing client list                                                                         0.24s  
  ⨯ it returns 401 when unauthenticated accessing single client                                                                       0.01s  
  ⨯ it returns 401 when unauthenticated accessing stats                                                                               0.01s  
  ⨯ it returns 401 when unauthenticated updating client status                                                                        0.01s  
  ⨯ it returns 403 when client role accesses client list                                                                              0.03s  
  ⨯ it returns 403 when client role accesses stats                                                                                    0.01s  
  ⨯ it returns 403 when client role accesses single client endpoint                                                                   0.01s  
  ⨯ it returns 403 when client role attempts status update                                                                            0.01s  
  ⨯ it allows coach to access client list                                                                                             0.01s  

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response                                                                                     0.03s  

   FAIL  Tests\Feature\IsolationTest
  ⨯ it coach A list total is 5 not 8 when coach B has 3 clients                                                                       0.01s  
  ⨯ it coach A viewing coach B client returns 403 not 404                                                                             0.01s  
  ⨯ it coach A cannot update coach B client status                                                                                    0.01s  
  ⨯ it coach A list does not include any of coach B clients                                                                           0.01s  

   FAIL  Tests\Feature\RosterListTest
  ⨯ it returns paginated own clients with no query parameters                                                                         0.01s  
  ⨯ it pagination total is scoped to authenticated coach                                                                              0.01s  
  ⨯ it search filters by client name                                                                                                  0.01s  
  ⨯ it search filters by client email                                                                                                 0.01s  
  ⨯ it search is case insensitive                                                                                                     0.01s  
  ⨯ it search does not return another coachs matching client                                                                          0.01s  
  ⨯ it search=% does not match all clients                                                                                            0.01s  
  ⨯ it filters by valid status active                                                                                                 0.01s  
  ⨯ it filters by valid status cancelled                                                                                              0.01s  
  ⨯ it ignores invalid status and returns all clients                                                                                 0.01s  
  ⨯ it paginates at 20 results per page                                                                                               0.01s  

   FAIL  Tests\Feature\SortTest
  ⨯ it sorts by name ascending                                                                                                        0.02s  
  ⨯ it sorts by name descending                                                                                                       0.01s  
  ⨯ it sorts by joined_at ascending                                                                                                   0.01s  
  ⨯ it sorts by joined_at descending                                                                                                  0.01s  
  ⨯ it sort=engagement sorts by last_activity_at descending                                                                           0.01s  
  ⨯ it sort=engagement forces desc even when direction=asc is passed                                                                  0.01s  
  ⨯ it invalid sort defaults to name ascending                                                                                        0.01s  
  ⨯ it invalid direction defaults to ascending                                                                                        0.01s  

   FAIL  Tests\Feature\StatsTest
  ⨯ it stats total counts only authenticated coach clients                                                                            0.02s  
  ⨯ it active count is correct                                                                                                        0.01s  
  ⨯ it cancelled count is correct                                                                                                     0.01s  
  ⨯ it past due count is correct                                                                                                      0.01s  
  ⨯ it newest joined date reflects only authenticated coach clients                                                                   0.01s  
  ⨯ it other coach clients do not affect stats counts                                                                                 0.01s  
  ⨯ it returns zero counts and null date when coach has no clients                                                                    0.01s  

   FAIL  Tests\Feature\StatusUpdateTest
  ⨯ it coach can update own client status                                                                                             0.02s  
  ⨯ it coach can update status to past_due                                                                                            0.01s  
  ⨯ it invalid status update returns 422 validation error                                                                             0.01s  
  ⨯ it missing status field returns 422 validation error                                                                              0.01s  
  ⨯ it cross-coach status update returns 403                                                                                          0.01s  
  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\AuthTest > it returns 401 when unauthenticated accessing client list                                                
  Expected response status code [401] but received 404.
Failed asserting that 404 is identical to 401.

  at tests\Feature\AuthTest.php:7
      3▕ use App\Models\User;
      4▕ use Laravel\Sanctum\Sanctum;
      5▕ 
      6▕ it('returns 401 when unauthenticated accessing client list', function () {
  ➜   7▕     $this->getJson('/api/clients')->assertUnauthorized();
      8▕ });
      9▕ 
     10▕ it('returns 401 when unauthenticated accessing single client', function () {
     11▕     $this->getJson('/api/clients/1')->assertUnauthorized();

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\AuthTest > it returns 401 when unauthenticated accessing single client                                              
  Expected response status code [401] but received 404.
Failed asserting that 404 is identical to 401.

  at tests\Feature\AuthTest.php:11
      7▕     $this->getJson('/api/clients')->assertUnauthorized();
      8▕ });
      9▕ 
     10▕ it('returns 401 when unauthenticated accessing single client', function () {
  ➜  11▕     $this->getJson('/api/clients/1')->assertUnauthorized();
     12▕ });
     13▕ 
     14▕ it('returns 401 when unauthenticated accessing stats', function () {
     15▕     $this->getJson('/api/clients/stats')->assertUnauthorized();

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\AuthTest > it returns 401 when unauthenticated accessing stats                                                      
  Expected response status code [401] but received 404.
Failed asserting that 404 is identical to 401.

  at tests\Feature\AuthTest.php:15
     11▕     $this->getJson('/api/clients/1')->assertUnauthorized();
     12▕ });
     13▕ 
     14▕ it('returns 401 when unauthenticated accessing stats', function () {
  ➜  15▕     $this->getJson('/api/clients/stats')->assertUnauthorized();
     16▕ });
     17▕ 
     18▕ it('returns 401 when unauthenticated updating client status', function () {
     19▕     $this->patchJson('/api/clients/1/status', ['status' => 'active'])->assertUnauthorized();

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\AuthTest > it returns 401 when unauthenticated updating client status                                               
  Expected response status code [401] but received 404.
Failed asserting that 404 is identical to 401.

  at tests\Feature\AuthTest.php:19
     15▕     $this->getJson('/api/clients/stats')->assertUnauthorized();
     16▕ });
     17▕ 
     18▕ it('returns 401 when unauthenticated updating client status', function () {
  ➜  19▕     $this->patchJson('/api/clients/1/status', ['status' => 'active'])->assertUnauthorized();
     20▕ });
     21▕ 
     22▕ it('returns 403 when client role accesses client list', function () {
     23▕     Sanctum::actingAs(User::factory()->create(['role' => 'client']));

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\AuthTest > it returns 403 when client role accesses client list                                    QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Mario Mitchell, lexie23@example.com, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, u5ot9nOFfy, client, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\AuthTest > it returns 403 when client role accesses stats                                          QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Benedict Kihn, odie40@example.net, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, pWyj9cE5N8, client, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\AuthTest > it returns 403 when client role accesses single client endpoint                         QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Dr. Letha Rice, lexus.anderson@example.net, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, HuiS0wvker, client, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\AuthTest > it returns 403 when client role attempts status update                                  QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Mr. Oscar Runolfsdottir, haven.williamson@example.net, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, kba59IBB0g, client, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\AuthTest > it allows coach to access client list                                                   QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Dr. Kavon Leffler, thompson.ellsworth@example.net, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, abPDtfK57Y, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\IsolationTest > it coach A list total is 5 not 8 when coach B has 3 clients                        QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Aurelie Kris V, ppadberg@example.com, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, 1Rra6UStht, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\IsolationTest > it coach A viewing coach B client returns 403 not 404                              QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Jay Reinger, blick.imogene@example.net, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, LkpG8GBycC, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\IsolationTest > it coach A cannot update coach B client status                                     QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Prof. Zackary White, mavis90@example.org, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, ZPcPv0kFik, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\IsolationTest > it coach A list does not include any of coach B clients                            QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Jena Parisian, sydni.dubuque@example.net, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, 3f0t1jYR0U, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\RosterListTest > it returns paginated own clients with no query parameters                         QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Nils Zieme, mara.heaney@example.com, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, M6uboMFX6d, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\RosterListTest > it pagination total is scoped to authenticated coach                              QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Ally Bahringer, samson89@example.org, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, FSIviMyIZ4, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\RosterListTest > it search filters by client name                                                  QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Chaya Grady, kathryn.robel@example.org, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, MLcNv2DZk7, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\RosterListTest > it search filters by client email                                                 QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Mr. Devante Bergnaum DVM, graciela22@example.com, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, LSa0YMoHv0, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\RosterListTest > it search is case insensitive                                                     QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Amya Gleason, harvey.amiya@example.net, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, 9GcAFL4F95, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\RosterListTest > it search does not return another coachs matching client                          QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Mercedes Schinner, antonette01@example.net, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, EmZP5WCVFV, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\RosterListTest > it search=% does not match all clients                                            QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Mr. Kenneth Klein I, hand.meagan@example.net, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, ouRUCMJb8P, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\RosterListTest > it filters by valid status active                                                 QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Prof. Stefanie Balistreri Sr., lou31@example.org, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, E9dyDYnhPI, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\RosterListTest > it filters by valid status cancelled                                              QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Ludie Fay, nsimonis@example.net, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, vZ5q5k87m1, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\RosterListTest > it ignores invalid status and returns all clients                                 QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Nelson Cronin, baumbach.ottis@example.net, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, VbvCPKldEr, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\RosterListTest > it paginates at 20 results per page                                               QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Armand Turner V, birdie.willms@example.org, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, kI2Ro2k7U9, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\SortTest > it sorts by name ascending                                                              QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Ms. Elisa Ebert I, zhamill@example.org, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, 8NCerXVUFe, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\SortTest > it sorts by name descending                                                             QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Eleazar Reinger, mitchell.darrion@example.com, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, sn1hbQVOLx, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\SortTest > it sorts by joined_at ascending                                                         QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Lorine Ebert, annamarie17@example.org, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, 0RvwvAB6ig, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\SortTest > it sorts by joined_at descending                                                        QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Lucienne Swaniawski, brennon77@example.org, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, XRByvswDHP, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\SortTest > it sort=engagement sorts by last_activity_at descending                                 QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Robert Fahey, austen89@example.com, 2026-07-06 00:28:18, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, Zf9HZKdY4D, coach, 2026-07-06 00:28:18, 2026-07-06 00:28:18))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\SortTest > it sort=engagement forces desc even when direction=asc is passed                        QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Piper Baumbach, bbosco@example.com, 2026-07-06 00:28:19, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, XNuGCq7uoz, coach, 2026-07-06 00:28:19, 2026-07-06 00:28:19))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\SortTest > it invalid sort defaults to name ascending                                              QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Dr. Emmitt Rice, juanita.ritchie@example.com, 2026-07-06 00:28:19, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, vaTOxdmjPH, coach, 2026-07-06 00:28:19, 2026-07-06 00:28:19))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\SortTest > it invalid direction defaults to ascending                                              QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Gloria Watsica, destini24@example.org, 2026-07-06 00:28:19, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, UUTD6i9axX, coach, 2026-07-06 00:28:19, 2026-07-06 00:28:19))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\StatsTest > it stats total counts only authenticated coach clients                                 QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Johnathan Pouros, wvon@example.com, 2026-07-06 00:28:19, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, aSlRPdlcVL, coach, 2026-07-06 00:28:19, 2026-07-06 00:28:19))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\StatsTest > it active count is correct                                                             QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Dr. Myrl Littel, flavie.turcotte@example.net, 2026-07-06 00:28:19, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, G2Dlnbp0VR, coach, 2026-07-06 00:28:19, 2026-07-06 00:28:19))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\StatsTest > it cancelled count is correct                                                          QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Tiana McCullough, vincenzo.gutkowski@example.org, 2026-07-06 00:28:19, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, 4iDRMxrdDi, coach, 2026-07-06 00:28:19, 2026-07-06 00:28:19))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\StatsTest > it past due count is correct                                                           QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Germaine Konopelski, jason91@example.com, 2026-07-06 00:28:19, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, NJIKawFYpo, coach, 2026-07-06 00:28:19, 2026-07-06 00:28:19))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\StatsTest > it newest joined date reflects only authenticated coach clients                        QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Lawrence Hoeger, hassan18@example.org, 2026-07-06 00:28:19, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, krL35iiLMD, coach, 2026-07-06 00:28:19, 2026-07-06 00:28:19))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\StatsTest > it other coach clients do not affect stats counts                                      QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Mr. Rodrigo Kertzmann IV, ihowell@example.org, 2026-07-06 00:28:19, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, f5UWXf8Mkq, coach, 2026-07-06 00:28:19, 2026-07-06 00:28:19))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\StatsTest > it returns zero counts and null date when coach has no clients                         QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Jalyn Roob, salvador.zulauf@example.net, 2026-07-06 00:28:19, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, j1QA3HVmxA, coach, 2026-07-06 00:28:19, 2026-07-06 00:28:19))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\StatusUpdateTest > it coach can update own client status                                           QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Lelia Fahey, agleichner@example.net, 2026-07-06 00:28:19, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, RWkIsPRhrN, coach, 2026-07-06 00:28:19, 2026-07-06 00:28:19))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\StatusUpdateTest > it coach can update status to past_due                                          QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Dr. Judd Larkin, hand.nicholaus@example.net, 2026-07-06 00:28:19, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, 7gPJDfZvrR, coach, 2026-07-06 00:28:19, 2026-07-06 00:28:19))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\StatusUpdateTest > it invalid status update returns 422 validation error                           QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Mohammed Yost, lauren82@example.org, 2026-07-06 00:28:19, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, oHfa2Ya2YD, coach, 2026-07-06 00:28:19, 2026-07-06 00:28:19))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\StatusUpdateTest > it missing status field returns 422 validation error                            QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Albin Stroman, tlarson@example.org, 2026-07-06 00:28:19, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, k5zlM1JQRd, coach, 2026-07-06 00:28:19, 2026-07-06 00:28:19))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\StatusUpdateTest > it cross-coach status update returns 403                                        QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "updated_at", "created_at") values (Dr. Enrico Haley Jr., johns.jean@example.org, 2026-07-06 00:28:19, $2y$04$GsIkACxAK7Sl.Q.AY/yIJuRLp7lVln/8mNxi8yi3IkYTM/e1FXJA6, xYWEZm8pBs, coach, 2026-07-06 00:28:19, 2026-07-06 00:28:19))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827


  Tests:    44 failed, 2 passed (6 assertions)
  Duration: 1.03s

  --------------------------------------------------------------------------------------------------------------

  After

  ------------------------------------------------------------------------------------------------------------------

   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Feature\AuthTest
  ✓ it returns 401 when unauthenticated accessing client list                                                                         0.21s  
  ✓ it returns 401 when unauthenticated accessing single client                                                                       0.01s  
  ✓ it returns 401 when unauthenticated accessing stats                                                                               0.01s  
  ✓ it returns 401 when unauthenticated updating client status                                                                        0.01s  
  ✓ it returns 403 when client role accesses client list                                                                              0.04s  
  ✓ it returns 403 when client role accesses stats                                                                                    0.01s  
  ✓ it returns 403 when client role accesses single client endpoint                                                                   0.01s  
  ✓ it returns 403 when client role attempts status update                                                                            0.01s  
  ✓ it allows coach to access client list                                                                                             0.01s  

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response                                                                                     0.03s  

   PASS  Tests\Feature\IsolationTest
  ✓ it coach A list total is 5 not 8 when coach B has 3 clients                                                                       0.02s  
  ✓ it coach A viewing coach B client returns 403 not 404                                                                             0.01s  
  ✓ it coach A cannot update coach B client status                                                                                    0.01s  
  ✓ it coach A list does not include any of coach B clients                                                                           0.02s  

   PASS  Tests\Feature\RosterListTest
  ✓ it returns paginated own clients with no query parameters                                                                         0.02s  
  ✓ it pagination total is scoped to authenticated coach                                                                              0.01s  
  ✓ it search filters by client name                                                                                                  0.01s  
  ✓ it search filters by client email                                                                                                 0.01s  
  ✓ it search is case insensitive                                                                                                     0.01s  
  ✓ it search does not return another coachs matching client                                                                          0.01s  
  ✓ it search=% does not match all clients                                                                                            0.01s  
  ✓ it filters by valid status active                                                                                                 0.01s  
  ✓ it filters by valid status cancelled                                                                                              0.01s  
  ✓ it ignores invalid status and returns all clients                                                                                 0.01s  
  ✓ it paginates at 20 results per page                                                                                               0.02s  

   PASS  Tests\Feature\SortTest
  ✓ it sorts by name ascending                                                                                                        0.02s  
  ✓ it sorts by name descending                                                                                                       0.01s  
  ✓ it sorts by joined_at ascending                                                                                                   0.01s  
  ✓ it sorts by joined_at descending                                                                                                  0.01s  
  ✓ it sort=engagement sorts by last_activity_at descending                                                                           0.01s  
  ✓ it sort=engagement forces desc even when direction=asc is passed                                                                  0.01s  
  ✓ it invalid sort defaults to name ascending                                                                                        0.01s  
  ✓ it invalid direction defaults to ascending                                                                                        0.01s  

   PASS  Tests\Feature\StatsTest
  ✓ it stats total counts only authenticated coach clients                                                                            0.02s  
  ✓ it active count is correct                                                                                                        0.01s  
  ✓ it cancelled count is correct                                                                                                     0.01s  
  ✓ it past due count is correct                                                                                                      0.01s  
  ✓ it newest joined date reflects only authenticated coach clients                                                                   0.01s  
  ✓ it other coach clients do not affect stats counts                                                                                 0.01s  
  ✓ it returns zero counts and null date when coach has no clients                                                                    0.01s  

   PASS  Tests\Feature\StatusUpdateTest
  ✓ it coach can update own client status                                                                                             0.02s  
  ✓ it coach can update status to past_due                                                                                            0.01s  
  ✓ it invalid status update returns 422 validation error                                                                             0.01s  
  ✓ it missing status field returns 422 validation error                                                                              0.01s  
  ✓ it cross-coach status update returns 403                                                                                          0.01s  

  Tests:    46 passed (90 assertions)
  Duration: 1.00s