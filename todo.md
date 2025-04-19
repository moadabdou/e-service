
## moad:

### User
- [*] login routing based on  the role

- [*] logout  

- [*] role checking  for prevent unautherized access 

- [ ] profile 

> [*] display  information

> [*] change user informations (phone, address, profile pecture ...)

> [ ] change  password 

- [ ] notification read

- [ ] ...  

### Admin 
- [*] register  new professor 

- [ ] email  check


### coordinateur



##  hassan


# 1. Professor Role (Enseignant) 👨‍🏫
In your web app, professors should be able to:

# Feature Description

[x]  View Available Modules	Show list of available "unités d’enseignement" (UE) for the next year.
[x] Choose Preferences	Select their preferred UEs (wish list).
[x] Calcul automatique de la charge horaire totale sélectionnée.
[x] Load Warning Alert if minimum required teaching load is not met.
[x] Assigned Modules	Show list of modules they are assigned to.
[ ] Upload Notes	Upload student grades (normal + retake).
[ ] View History	See teaching assignments from past years.

# Technically, i need to:

[x] Allow professors to select UEs and track total hours.

[x] Save and load their previous choices.

[ ] Upload files (PDF/Excel for grades).

[ ] Role-based access (only see their part).



# 2. Chef de Département Role 🧑‍💼
In your web app, the department head should be able to:


# Feature	Description : 
[ ] List UEs	View all teaching units in their department.
[ ] List Professors	See all profs in their department.
[ ] Assign Modules	Assign one or more UEs to professors.
[ ] Validate Requests	Approve/Reject professors’ preferences.
[ ] Generate Load	Auto-generate total hours per professor.
[ ] Detect Underload	Highlight profs under minimum hours.
[ ] View Vacant UEs	See unassigned UEs and validate.
[ ] View History	See assignments from previous years.
[ ] Reporting	Generate summaries/statistics.
[ ] Excel Import/Export	Export and import data via Excel.

# Technically, you need to:

[ ] Build a secured dashboard with a list of all profs and UEs.

[ ] Allow manual assignment and approval of UEs.

[ ] Calculate total hours per prof.

[ ] Highlight profs with insufficient load (use a red color or something 🔥).

[ ] Allow export/import of data using Excel.

[ ] Show stats like bar charts or tables for reporting.