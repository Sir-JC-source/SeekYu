# TODO: Remove 'duration' field from Leave model and calculate dynamically

## Steps:
- [ ] Update Leave model: Remove 'duration' from fillable, add getDurationAttribute accessor to calculate days from date_from and date_to.
- [ ] Update LeaveController: Remove 'duration' from validation rules.
- [ ] Update views: Remove duration select field from resources/views/Leaves/request.blade.php and resources/views/Leaves/index.blade.php, update JS logic.
- [ ] Update display views: resources/views/Leaves/pending.blade.php and resources/views/Leaves/processed.blade.php to use $leave->duration.
- [ ] Create migration: Drop 'duration' column from 'leaves' table.
- [ ] Test the changes: Ensure leave days are calculated correctly, forms work without duration field.
