# PHPProject

This is my first PHP project I made for school.

It is a web-site with 3 different types of user: Admin, Manager and Executant. Each type can see and do different things.<br>  
Admin - can do everything in the app.<br>
Manager - is a person who creates tasks and assigns them to Executants. He can edit, delete etc... tasks but only ones he made.<br>
Executant - can see tasks he was assigned to, write comments underneath task, he can check that he has finished his part of task... etc.<br>
The main goal is making tasks. And working with them.<br>

You can see the whole project text inside of "tekstzadatka" file. It is on Serbian and English(Translated by google translate).<br>
Database is located in SQL DB Folder, just import projekat11.sql to PHPMYADMIN<br>


REGISTRATION PAGE:<br>
It all starts here, user can register, and his type will be executant by default.<br>
He will receive activation link, by clicking it, his account gets activated and then he can login.<br>
Activation link lasts only for 30 mins.<br>

There are some basic checks while registering, i didn't go deep like making passwords checks etc.
![image](https://github.com/mr-devD/PHPProject/assets/93098789/b809d7ef-5f39-4a11-b61d-a25b63da2618)



LOGIN PAGE:<br>
Basic LOGIN page with email and password inputs.<br>
![image](https://github.com/mr-devD/PHPProject/assets/93098789/643f96af-d37f-4357-b614-e6d74a62e565)

IF USER HASN'T ACTIVATED HIS ACCOUNT WHEN HE TRIES TO LOGIN HE WILL GET THIS PAGE:<br>
Here it says that his account hasn't been activated yet and there is a button to request a new link.<br>
![image](https://github.com/mr-devD/PHPProject/assets/93098789/876f12d3-38f9-4114-b5fc-6bfcd2f2edd2)


EXECUTANT MAIN PAGE:<br>
After successful login, if the user is executant his default page will be tasks page which is a table of tasks he is assigned to.<br>
Green background of task means that task is completed.<br>
Yellow means it is canceled.<br>
And no background(white) means it is still active.<br>
User can access any task by clicking on task name.<br>

This whole page is sortable and searchable, I used DATATABLES to make this work.<br>
![image](https://github.com/mr-devD/PHPProject/assets/93098789/9135caed-0b64-48b2-98c9-83d016c4ad89)

TASK PAGE:<br>
First part of page is Infromation of selected task:<br>
And there is a list of all executants assigned to that task, user can click "DONE" button next to his name which will make his name green, which means he finished his part.<br>
![image](https://github.com/mr-devD/PHPProject/assets/93098789/605a7232-f011-49ad-a11f-9fd491a2378c)

If user is ADMIN OR MANAGER he will have additional buttons: COMPLETE, CANCEL, EDIT, DELETE<br>
![image](https://github.com/mr-devD/PHPProject/assets/93098789/45b65466-db11-4bd3-99f4-a18fc2ae5c00)



Second part is Comments part where user can leave a comment about task. and he also can delete his comments<br>
![image](https://github.com/mr-devD/PHPProject/assets/93098789/ef1d8c58-6292-485c-8366-736b38101451)

PROFILE PAGE:<br>
Executants and Managers can see only their profile while ADMIN can access anyones.<br>
Executants and Managers can edit basic info while ADMIN can edit everything<br>
![image](https://github.com/mr-devD/PHPProject/assets/93098789/34bf558d-997d-4ad9-ae33-05826543c75d)


TASK GROUPS PAGE:<br>
Accessible only by admins and managers.<br>
Here they can add new task groups or edit existing ones.<br>
![image](https://github.com/mr-devD/PHPProject/assets/93098789/895155d4-71da-4edd-918f-9e16ad7aeaa7)

ADDING TASKS:<br>
Task creation is located at the same page as list of all tasks.<br>
But is only visible to ADMIN AND MANAGER<br>
![image](https://github.com/mr-devD/PHPProject/assets/93098789/7d611b37-57b5-4fe0-9bcb-02402fa07835)

ADMINS ONLY:<br>
USERS PAGE:<br>
Here is a table of all users, searchable and sortable.<br>
Admin can click on users name, which will lead him on profile page of that user where he can edit his info.<br>
![image](https://github.com/mr-devD/PHPProject/assets/93098789/259ac4d1-a9e2-4903-84ee-facb0f770a5e)


USER TYPES PAGE:<br>
Admin can create new user types and edit existing ones.<br>
![image](https://github.com/mr-devD/PHPProject/assets/93098789/40df1498-6c7e-4761-9475-1eaa685d4a16)




