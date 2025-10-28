# TODO: Add Duration-Based Credit Calculation Function to LeaveController

## Tasks
- [x] Add private function `calculateCredits($duration, $date_from, $date_to)` to LeaveController
- [x] Update store method to use the function and set date_to automatically for Whole/Half shifts
- [x] Update approve method to use the function for credit deduction
- [x] Update validation in store to allow Multi-Day and adjust date rules
- [x] Sync duration options in views (add Multi-Day to index.blade.php if needed)
- [x] Add JavaScript to disable past dates in date inputs
- [x] Add JavaScript to auto-set date_to for Whole/Half shifts
- [x] Add warning/error handler for insufficient leave credits in form
- [x] Add server-side validation for sufficient credits in store method
- [x] Test the functionality

## Information Gathered
- LeaveController store method creates leave with fixed credits from user
- Approve method has basic logic for Whole Shift (1), Half (0.5), Multi-Day (diff days +1)
- Views have different duration options: index.blade.php has Whole Shift, Half-Shift Early Out, Half-Shift Late In; request.blade.php has those plus Multi-Day
- Need to enforce date ranges: Whole/Half = 1 day, Multi-Day = 2-10 days
- Credits: Whole = 1, Half = 0.5, Multi-Day = number of days in range
- Additional requirements: Disable past dates, auto-set date_to for single-day leaves, warn/error on insufficient credits

## Plan
- Add calculateCredits function:
  - Whole Shift: return 1, set date_to = date_from
  - Half-Shift: return 0.5, set date_to = date_from
  - Multi-Day: calculate days = diff(date_from, date_to) + 1, return days (clamp 2-10)
- Update store: call function, set leave_credits to calculated value, adjust date_to, add credit validation
- Update approve: use function for daysToDeduct
- Update validation: add Multi-Day to in: rule, adjust date_to after_or_equal for Multi-Day
- Ensure views are consistent
- Add JS for date restrictions and credit warnings

## Followup Steps
- [x] Test the functionality

# TODO: Implement Guard Scheduling System

## Tasks
- [x] Create Schedule model and migration
- [x] Update SecurityController with scheduling methods
- [x] Add routes for scheduling functionality
- [x] Update AssignSchedule.blade.php view
- [x] Create GuardSchedule.blade.php view for calendar interface
- [x] Run migrations to create schedules table
- [x] Test the scheduling functionality

## Information Gathered
- Need a schedules table with guard_id, schedule_date, shift_in, shift_out, created_by, updated_by
- SecurityController needs assignSchedule, showGuardSchedule, storeSchedule methods
- Routes need to be added for assign, assign/{guardId}, assign/{guardId}/store
- AssignSchedule view should list guards with assign buttons
- GuardSchedule view should show 3-month calendar with time inputs for each day
- Need to handle create/update/delete schedules based on shift times provided

## Plan
- Create migration for schedules table
- Add Schedule model
- Update SecurityController imports and methods
- Add routes in web.php
- Update AssignSchedule view to show guards table
- Create GuardSchedule view with calendar interface
- Run migrations
- Test functionality

## Followup Steps
- [x] Test the scheduling functionality
