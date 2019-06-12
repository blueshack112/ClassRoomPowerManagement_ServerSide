insert into tbl_relays (relay_id, no_of_associated_lights, no_of_assiocuated_fans, no_of_assiocuated_acs) 
values  (101, 3, 3, 1),
		(102, 0, 0, 1),
		(103, 4, 4, 0),
		(104, 4, 4, 0),
		(105, 4, 4, 0),
        (106, 4, 4, 0);
        
select * from  tbl_relays;

insert into tbl_teachers (teacher_id, teacher_first_name, teacher_last_name,teacher_designation)
values  (1001, "Shafaq", "Sohail", "asst_prof"),
		(1002, "Afzal", "Hussain", "asst_prof"),
		(1003, "Iqbaluddin", "Khan", "asst_prof"),
		(1004, "Adnan", "Jaffri", "asst_prof"),
		(1005, "Shams ul", "Arfeen", "asst_prof"),
		(1006, "Salman", "Shah", "asst_prof"),
		(1007, "Kamil", "Sidiqui", "asst_prof"),
		(1008, "Adeel", "Mannan", "asst_prof"),
		(1009, "Imran", "Khan", "asst_prof"),
		(1010, "Zafar", "Ahmed", "asst_prof"),
		(1011, "Noman", "Siddiqui", "asst_prof"),
		(1012, "Aqeel", "Ur Rehman", "HOD"),
		(1013, "Adnan","Ahmed", "asst_prof"),
		(1014, "Asad","Ur Rehman", "asst_prof"),
		(1015, "Suboohi","Mahmood", "asst_prof");


insert into tbl_login_accounts (account_id, permission_level, password)
values  (1001, "Teacher", "Hamdard123"),
		(1002, "Teacher", "Hamdard123"),
		(1003, "Teacher", "Hamdard123"),
		(1004, "Teacher", "Hamdard123"),
		(1005, "Teacher", "Hamdard123"),
		(1006, "Teacher", "Hamdard123"),
        (1007, "Teacher", "Hamdard123"),
        (1008, "Teacher", "Hamdard123"),
        (1009, "Teacher", "Hamdard123"),
        (1010, "Teacher", "Hamdard123"),
        (1011, "Teacher", "Hamdard123"),
        (1012, "HOD", "HamdardHOD123"),
        (1013, "Teacher", "Hamdard123"),
        (1014, "Teacher", "Hamdard123"),
        (1015, "QMD", "HamdardQMD123");


select * from tbl_teachers;

insert into tbl_rooms(room_id, capacity, no_of_lights, no_of_fans, no_of_ac)
values  (1001, 60, 14, 9, 2),
		(1002, 60, 14, 9, 2),
		(1003, 60, 14, 9, 2),
		(1004, 60, 14, 9, 2),
		(1005, 60, 14, 9, 2),
		(1006, 60, 14, 9, 2),
		(1007, 30, 7, 4, 0),
		(1008, 30, 7, 4, 0),
		(1009, 60, 14, 9, 2),
		(1010, 60, 14, 9, 2),
		(1011, 30, 7, 4, 0),
		(1012, 30, 7, 4, 0),
		(1013, 60, 14, 9, 2),
		(1014, 60, 14, 9, 2),
		(1015, 60, 14, 9, 2);

insert into tbl_courses(course_id , teacher_id, course_name, course_credit_hours,total_students_enrolled)
values  (1001, 1001, "Basic Electronics", 				2, 	27),
		(1002, 1001, "Functonal English", 				2, 	46),
		(1003, 1001, "Pakistan Studies", 				2, 	54),
		(1004, 1001, "Islamic Studies", 				2, 	43),
		(1005, 1002, "Numerical Computing", 			3, 	27),
		(1006, 1002, "Compiler Construction", 			3, 	44),
		(1007, 1002, "Marketing and Management", 		3, 	55),
		(1008, 1002, "Discrete Structure", 				3, 	33),
		(1009, 1002, "Objecct Oriented Programming", 	3, 	45),
		(1010, 1002, "Operating System", 				3, 	42),
		(1011, 1003, "Software Project Management",		3, 	23),
		(1012, 1003, "Financial Management", 			3, 31);

select * from tbl_courses;

insert into tbl_Schedule(room_id, 	course_id, 	slot, 	day_of_week, 	class_length)
values  				(1001, 		1001, 		1, 		1,				2),
						(1001, 		1002, 		3, 		1,				2),
        				(1001, 		1003, 		5, 		1,				2),
        				(1001, 		1004, 		1, 		2,				2),
        				(1001, 		1005, 		7, 		1,				1),
        				(1001, 		1005, 		3, 		2,				2),
        				(1001, 		1006, 		7, 		3,				1),
        				(1001, 		1006, 		5, 		2,				2),
        				(1001, 		1007, 		7, 		2,				1),
        				(1001, 		1007, 		1, 		3,				2),
        				(1001, 		1008, 		7, 		4,				1),
        				(1001, 		1008, 		3, 		3,				2),
        				(1001, 		1009, 		1, 		5,				1),
        				(1001, 		1009, 		5, 		3,				2),
        				(1001, 		1010, 		2, 		5,				1),
        				(1001, 		1010, 		1, 		4,				2),
        				(1001, 		1011, 		3, 		5,				1),
						(1001, 		1011, 		3, 		4,				2),
						(1001, 		1012, 		4, 		5,				1),
						(1001, 		1012, 		5, 		4,				2);



        
select * from tbl_Schedule;

insert into tbl_room_status values (1001,1001,101102103,25,curdate(), 1);

/*Below is bullshit*/
insert into tbl_room_status(room_id, course_id, relay_used, attendance, date, slot)
values
        (01, 0002, 101, 22, 2019-01-07, 1),
		(01, 0002, 103, 22, 2019-01-07, 1),
		(01, 0002, 104, 22, 2019-01-07, 1),
		(01, 0002, 105, 22, 2019-01-07, 1),

		(01, 0002, 101, 22, 2019-01-07, 2),
		(01, 0002, 103, 22, 2019-01-07, 2),
		(01, 0002, 104, 22, 2019-01-07, 2),
		(01, 0002, 105, 22, 2019-01-07, 2),
        
        (01, 0010, 101, 37, 2019-01-07, 3),
		(01, 0010, 102, 37, 2019-01-07, 3),
		(01, 0010, 103, 37, 2019-01-07, 3),
		(01, 0002, 104, 37, 2019-01-07, 3),
		(01, 0002, 105, 37, 2019-01-07, 3),
        
        (01, 0010, 101, 37, 2019-01-07, 4),
		(01, 0010, 102, 37, 2019-01-07, 4),
		(01, 0010, 103, 37, 2019-01-07, 4),
		(01, 0010, 104, 37, 2019-01-07, 4),
		(01, 0010, 105, 37, 2019-01-07, 4),
        
        (01, 0005, 101, 24, 2019-01-07, 5),
		(01, 0005, 103, 24, 2019-01-07, 5),
		(01, 0005, 104, 24, 2019-01-07, 5),
		
        (01, 0005, 101, 24, 2019-01-07, 6),
		(01, 0005, 103, 24, 2019-01-07, 6),
		(01, 0005, 104, 24, 2019-01-07, 6),
        
		(01, 0009, 101, 41, 2019-01-08, 1),
		(01, 0009, 102, 41, 2019-01-08, 1),
		(01, 0009, 103, 41, 2019-01-08, 1),
		(01, 0009, 104, 41, 2019-01-08, 1),
		(01, 0009, 105, 41, 2019-01-08, 1),
        
        (01, 0006, 101, 38, 2019-01-08, 3),
		(01, 0006, 102, 38, 2019-01-08, 3),
		(01, 0006, 103, 38, 2019-01-08, 3),
		(01, 0006, 104, 38, 2019-01-08, 3),
		(01, 0006, 105, 38, 2019-01-08, 3),
        
        (01, 0006, 101, 38, 2019-01-08, 4),
		(01, 0006, 102, 38, 2019-01-08, 4),
		(01, 0006, 103, 38, 2019-01-08, 4),
		(01, 0006, 104, 38, 2019-01-08, 4),
		(01, 0006, 105, 38, 2019-01-08, 4),
        
        
        (01, 0012, 101, 38, 2019-01-08, 7),
		(01, 0012, 103, 38, 2019-01-08, 7),
		(01, 0012, 104, 38, 2019-01-08, 7),
		(01, 0012, 105, 38, 2019-01-08, 7),
        
        
        (01, 0008, 101, 31, 2019-01-09, 1),
		(01, 0008, 102, 31, 2019-01-09, 1),
		(01, 0008, 103, 31, 2019-01-09, 1),
		(01, 0008, 104, 31, 2019-01-09, 1),
		
        
        (01, 0011, 101, 23, 2019-01-09, 4),
		(01, 0011, 103, 23, 2019-01-09, 4),
		(01, 0011, 104, 23, 2019-01-09, 4),
        
        
        (01, 0007, 101, 48, 2019-01-10, 1),
		(01, 0007, 102, 48, 2019-01-10, 1),
		(01, 0007, 103, 48, 2019-01-10, 1),
		(01, 0007, 104, 48, 2019-01-10, 1),
		(01, 0007, 105, 48, 2019-01-10, 1),
        
        (01, 0007, 101, 48, 2019-01-10, 2),
		(01, 0007, 102, 48, 2019-01-10, 2),
		(01, 0007, 103, 48, 2019-01-10, 2),
		(01, 0007, 104, 48, 2019-01-10, 2),
		(01, 0007, 105, 48, 2019-01-10, 2),
		
        (01, 0011, 101, 20, 2019-01-10, 6),
		(01, 0011, 103, 20, 2019-01-10, 6),
		(01, 0011, 104, 20, 2019-01-10, 6),
		
        (01, 0001, 101, 24, 2019-01-11, 3),
		(01, 0001, 103, 24, 2019-01-11, 3),
		(01, 0001, 104, 24, 2019-01-11, 3);
        
select * from tbl_room_status;


        

insert into tbl_history(date, room_id, slot, credit_hour, relay_used)
values  (2019-01-07, 01, 1, 2, 4),
		(2019-01-07, 01, 2, 2, 4),
        (2019-01-07, 01, 3, 2, 5),
        (2019-01-07, 01, 4, 2, 5),
        (2019-01-07, 01, 5, 2, 3),
        (2019-01-07, 01, 6, 2, 3),
        
        (2019-01-08, 01, 1, 1, 5),
        (2019-01-08, 01, 3, 2, 5),
        (2019-01-08, 01, 4, 2, 5),
        (2019-01-08, 01, 7, 1, 4),
        
        (2019-01-09, 01, 1, 1, 4),
        (2019-01-09, 01, 4, 1, 3),
        
        (2019-01-10, 01, 1, 2, 5),
        (2019-01-10, 01, 2, 2, 5),
        (2019-01-10, 01, 6, 1, 3),
        
        (2019-01-11, 01, 1, 2, 3),
        (2019-01-11, 01, 2, 2, 3);

select * from tbl_history;

/*View for getting teacher id against courses*/
select tbl_courses.teacher_id, tbl_schedule.room_id, tbl_schedule.course_id, tbl_schedule.day_of_week, tbl_schedule.slot, tbl_schedule.class_length
from tbl_courses left join tbl_schedule 
on tbl_courses.course_id = tbl_schedule.course_id where teacher_id = 1001;

/*View for getting Room status for HOD's app panel*/
select tbl_room_status.class_date, tbl_room_status.room_id, tbl_courses.course_name, tbl_teachers.teacher_first_name,
 tbl_teachers.teacher_last_name, tbl_schedule.class_length, tbl_room_status.attendance
from tbl_room_status 
left join tbl_courses on tbl_room_status.course_id = tbl_courses.course_id
left join tbl_teachers on tbl_courses.teacher_id = tbl_teachers.teacher_id
left join tbl_schedule on tbl_courses.course_id = tbl_schedule.course_id;