# TODO: Wire HR Officer Games into Application Flow

## Plan
- Ensure HR Officer position triggers an HR assessment flow instead of immediate application submission.
- Add routes/endpoints for the HR assessment screens.
- Implement combined HR assessment submission that creates `JobApplication` and saves HR game scores into `ApplicantGameScore`.
- Ensure correct `job_application_id` linkage and session handling (like guard flow).

## Steps
1. Update `app/Http/Controllers/JobPosting/JobPostingController.php::apply()`
   - If position == `HR Officer`, redirect to HR assessment start route.
   - Store `pending_application_job_id` in session (same as guard).
2. Update `routes/web.php`
   - Add applicant routes for HR games (start + step pages + submit).
3. Implement HR assessment controller methods (in existing JobPostingController)
   - `showHrResumeGame()` (HR/1)
   - `showHrSortingGame()` (HR/2)
   - `showHrClientGuardGame()` (HR/3)
   - `submitHrGameScores()` to create `JobApplication` and insert 3 rows into `ApplicantGameScore`.
4. Confirm that front-end calls submit endpoint with expected field names.
5. Add/verify Blade views or wiring to serve existing HR HTML assets.
6. Smoke test:
   - Apply as HR Officer -> plays HR games -> application created -> scores stored.

