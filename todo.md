
## moad:

### User
- [x] login routing based on  the role

- [x] logout  

- [x] role checking  for prevent unautherized access 

- [ ] profile 

> [x] display  information

> [x] change user informations (phone, address, profile pecture ...)

> [x] change  password 

- [x] notification read

- [ ] ...  

### Admin 
- [x] register  new professor 

- [x] email  check

- [ ] actions on All users on website seperated by their roles 

    - [ ] see them 
    - [ ] see their profile and edit their info
    - [ ] desactivate and activate and delete 
    - [ ] reset their password (an email must  be sent)
    - [ ] make a professor a deparetement head or class coordinator  and the inverse 
        > if another professor is already a coordinator/head and the same departement/class, a message should appear asking to wheter to replace him ( only if the same year )
    - ... 

- [ ] actions on  depatements 

    - [ ] see all  deparetments 
    - [ ] can see all the classes in a deparetment 
    


### coordinateur



##  hassan


# 1. Professor Role (Enseignant) 👨‍🏫
In your web app, professors should be able to:

# Feature Description

[x]  View Available Modules	Show list of available "unités d’enseignement" (UE) for the next year.
[x] Choose Preferences	Select their preferred UEs (wish list).
[x] Calcul automatique de la charge horaire totale sélectionnée.
[x] Load Warning Alert if minimum required teaching load is not met.
[x] Assigned Modules Show list of modules they are assigned to.
[x] Upload Notes Upload student grades (normal + retake).
[x] View HistoryUploaded Notes
[ ] View History	See teaching assignments from past years.

# Technically, i need to:

[x] Allow professors to select UEs and track total hours.

[x] Save and load their previous choices.

[x] Upload files (PDF/Excel for grades).

[x] Role-based access (only see their part).



# 2. Chef de Département Role 🧑‍💼
In your web app, the department head should be able to:


# Feature	Description : 
[x] List UE View all teaching units in their department.
[x] List Professors	See all profs in their department.
[x] Assign Modules	Assign one or more UEs to professors.
[x] Validate Requests	Approve/Reject professors’ preferences.
[x] Generate Load	Auto-generate total hours per professor.
[x] Detect Underload	Highlight profs under minimum hours.
[x] View Vacant UEs	See unassigned UEs and validate.
[x] View History	See assignments from previous years.
[ ] Reporting	Generate summaries/statistics.
[x] Excel Export	Export and import data via Excel.

# Technically, you need to:

[x] Build a secured dashboard with a list of all profs and UEs.

[x] Allow manual assignment and approval of UEs.

[x] Calculate total hours per prof.

[x] Highlight profs with insufficient load (use a red color or something 🔥).

[x] Allow export of data using Excel.

[x] Show stats like bar charts or tables for reporting.