# Integrate Guard Assessment Games into Application Process

## Tasks
- [ ] Modify JobPostingController@apply to redirect to games for Head Guard/Security Guard positions
- [ ] Create applicant.games.gate view embedding GUARD/1 - Gate Screening.html
- [ ] Create applicant.games.bag view embedding GUARD/2 - Bag Inspection.html
- [ ] Create applicant.games.memory view embedding GUARD/3 - Memory Test.html
- [ ] Add routes for game views and score submission
- [ ] Implement score capture in game views using JS events and session storage
- [ ] Create method to finalize application after games, saving JobApplication and ApplicantGameScore
- [ ] Update admin application views to display game scores
- [ ] Test the full flow: apply -> games -> application submitted -> admin sees scores
