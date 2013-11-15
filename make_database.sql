/* 
 * Drop the database if it already exists
 * this will give us a fresh start 
 */
DROP DATABASE IF EXISTS bookstore;

/*
 * Create the database.
 */
CREATE DATABASE IF NOT EXISTS bookstore;

/*
 * Use newly created bookstore.
 */
use bookstore

/*
 * Create table Department.
 * Used to store information about University Departments.
 */
CREATE TABLE IF NOT EXISTS Department
(
    dept_code CHAR(4) NOT NULL PRIMARY KEY,
    dept_name VARCHAR(50) NOT NULL
);

/*
 * Create table Student.
 * Used to store information about students enrolled in the University.
 */
CREATE TABLE IF NOT EXISTS Student
( 
    id_number INTEGER NOT NULL PRIMARY KEY,
    dob DATE NOT NULL,
    family_name VARCHAR(50) NOT NULL,
    given_name VARCHAR(50) NOT NULL,
    email VARCHAR(254) NOT NULL, 
    phone_number VARCHAR(15)  NOT NULL,
    city VARCHAR(50), 
    province VARCHAR(50), 
    postal_code CHAR(6), 
    street_number VARCHAR(20), 
    street_name VARCHAR(80), 
    appt_number VARCHAR(20)
);

/*
 * Create table Bookorder.
 * Used to store information about book orders made by students.
 */
CREATE TABLE IF NOT EXISTS Bookorder
(
    order_id INTEGER NOT NULL AUTO_INCREMENT, 
    order_date DATE NOT NULL,
    id_number INTEGER NOT NULL,
    PRIMARY KEY (order_id),
    FOREIGN KEY (id_number) REFERENCES Student(id_number)
);

/*
 * Create table Bookstore.
 * Used to store information about Bookstores throughout the University.
 */
CREATE TABLE IF NOT EXISTS Bookstore
(
    store_id INTEGER NOT NULL AUTO_INCREMENT, 
    store_name VARCHAR(50) NOT NULL,
    PRIMARY KEY (store_id)
);

/*
 * Create table Employee.
 * Used to store ID and password about Employees working at the store.
 */
CREATE TABLE IF NOT EXISTS Employee
(
    employee_id INTEGER NOT NULL PRIMARY KEY,
    password_hash VARCHAR(255) NOT NULL
);

/*
 * Create table EmployeeList.
 * Used to identify which employees work at which bookstores.
 */
CREATE TABLE IF NOT EXISTS EmployeeList
(
    employee_id INTEGER NOT NULL,
    store_id INTEGER NOT NULL,
    FOREIGN KEY (store_id) REFERENCES Bookstore(store_id),
    FOREIGN KEY (employee_id) REFERENCES Employee(employee_id),
    PRIMARY KEY (store_id, employee_id)
);

/*
 * Create table Publisher.
 * Used to store information about Book publishers.
 */
CREATE TABLE IF NOT EXISTS Publisher
( 
    group_id enum('0','1') NOT NULL,
    publisher_id INTEGER NOT NULL,
    publisher_name VARCHAR(255) NOT NULL,
    city VARCHAR(50),
    province_state VARCHAR(50),
    postal_code_zip VARCHAR(10),
    country VARCHAR(160),
    street_number VARCHAR(20),
    street_name VARCHAR(80), 
    appt_number VARCHAR(20),
    post_office_box VARCHAR(20),
    PRIMARY KEY (publisher_id, group_id)
);

/*
 * Create table Book.
 * Used to store information about Books.
 */
CREATE TABLE IF NOT EXISTS Book
(
    isbn VARCHAR(17) NOT NULL PRIMARY KEY,
    title VARCHAR(100) NOT NULL, 
    year YEAR,
    price INTEGER NOT NULL,
    image_url VARCHAR(255) DEFAULT "images/image.png"
);

/*
 * Create table BookPublisher.
 * Used to identify which publisher published which book.
 */
CREATE TABLE IF NOT EXISTS BookPublisher
(
    isbn VARCHAR(17) NOT NULL,
    group_id enum('0', '1') NOT NULL,
    publisher_id INTEGER NOT NULL,
    FOREIGN KEY (isbn) REFERENCES Book(isbn),
    FOREIGN KEY (publisher_id, group_id) REFERENCES Publisher(publisher_id,    group_id),
    PRIMARY KEY (isbn, publisher_id, group_id)
);

/*
 * Create table Author.
 * Used to store information about Book Authors.
 */
CREATE TABLE IF NOT EXISTS Author
(
    family_name VARCHAR(50) NOT NULL,
    given_name VARCHAR(50) NOT NULL,
    PRIMARY KEY (family_name, given_name)
);

/*
 * Create table Course.
 * Used to store information about University courses.
 */
CREATE TABLE IF NOT EXISTS Course
(
    course_number INTEGER NOT NULL, 
    title VARCHAR(100) NOT NULL, 
    description text,
    dept_code CHAR(4) NOT NULL,
    FOREIGN KEY (dept_code) REFERENCES Department(dept_code),
    PRIMARY KEY (course_number, dept_code)
);

/*
 * Create table Section.
 * Used to identify when courses run throughout the term.
 */
CREATE TABLE IF NOT EXISTS Section
(
    term_number enum('0', '1', '2') NOT NULL,
    section_code CHAR(1) NOT NULL,                
    slot INTEGER NOT NULL, 
    course_number INTEGER NOT NULL, 
    dept_code CHAR(4) NOT NULL,
    FOREIGN KEY (course_number, dept_code) REFERENCES Course(course_number, dept_code),
    PRIMARY KEY (term_number, section_code, course_number, dept_code)
);

/*
 * Create table Enrolled.
 * Used to identify which courses and sections a student is enrolled in.
 */
CREATE TABLE IF NOT EXISTS Enrolled
(
    id_number INTEGER NOT NULL,
    term_number enum('0', '1', '2') NOT NULL,
    section_code CHAR(1) NOT NULL,
    course_number INTEGER NOT NULL,
    dept_code CHAR(4),
    FOREIGN KEY (id_number) REFERENCES Student(id_number), 
    FOREIGN KEY (term_number, section_code, course_number, dept_code) REFERENCES Section(term_number, section_code, course_number, dept_code),
    PRIMARY KEY (id_number, section_code, term_number, course_number, dept_code)
);

/*
 * Create table Contains.
 * Used to identify which books are contained in a particular bookorder.
 */
CREATE TABLE IF NOT EXISTS Contains
(
    isbn VARCHAR(17) NOT NULL, 
    order_id INTEGER NOT NULL, 
    received BOOLEAN DEFAULT false, 
    quantity INTEGER NOT NULL, 
    FOREIGN KEY (order_id) REFERENCES Bookorder(order_id), 
    FOREIGN KEY (isbn) REFERENCES Book(isbn), 
    PRIMARY KEY (order_id, isbn)
);

/*
 * Create table Requires.
 * Used to identify which books are required by which course.
 */
CREATE TABLE IF NOT EXISTS Requires
(
    isbn VARCHAR(17) NOT NULL,
    term_number enum('0', '1', '2') NOT NULL,
    section_code CHAR(1) NOT NULL,
    course_number INTEGER NOT NULL,
    dept_code CHAR(4) NOT NULL,
    FOREIGN KEY (isbn) REFERENCES Book(isbn),
    FOREIGN KEY (term_number, section_code, course_number, dept_code) REFERENCES Section(term_number, section_code, course_number, dept_code),
    PRIMARY KEY (isbn, term_number, section_code, course_number, dept_code)
);

/*
 * Create table Stocks.
 * Used to store information about which books are stocked in which bookstore.
 */
CREATE TABLE IF NOT EXISTS Stocks
(
    isbn VARCHAR(17) NOT NULL,
    store_id INTEGER NOT NULL,
    quantity INTEGER DEFAULT 0,
    FOREIGN KEY (isbn) REFERENCES Book(isbn),
    FOREIGN KEY (store_id) REFERENCES Bookstore(store_id),
    PRIMARY KEY (isbn, store_id)
);

/*
 * Create table Written.
 * Used to identify which authors wrote which books.
 */
CREATE TABLE IF NOT EXISTS Written
(
    isbn VARCHAR(17) NOT NULL,
    family_name VARCHAR(50),
    given_name VARCHAR(50),
    FOREIGN KEY (isbn) REFERENCES Book(isbn),
    FOREIGN KEY (family_name, given_name) REFERENCES Author(family_name, given_name),
    PRIMARY KEY (isbn, family_name, given_name)
);

/*
 * Create table Phone.
 * Used to identify the phone numbers associated with each department.
 */
CREATE TABLE IF NOT EXISTS Phone
(
    phone_number VARCHAR(15) NOT NULL,
    dept_code CHAR(4) NOT NULL,
    FOREIGN KEY (dept_code) REFERENCES Department(dept_code),
    PRIMARY KEY (phone_number, dept_code)
);


/*
 * Create a view to simplify access to data.
 * Displays the student ID, book ISBN, title, and Price.
 */
CREATE VIEW order_view 
    AS SELECT  
        a.id_number, 
        b.isbn, c.title, 
        c.price 
            FROM 
            Bookorder a INNER JOIN Contains b 
                ON a.order_id = b.order_id 
            INNER JOIN 
                Book c 
                    ON b.isbn = c.isbn;


/*
 * Create a view to simplify access to required books.
 */

CREATE VIEW student_req_books
    AS SELECT  
        a.id_number, 
        b.term_number, b.section_code, b.course_number, b.dept_code,
        c.* 
        FROM Enrolled a INNER JOIN Requires b 
          ON a.term_number = b.term_number 
            AND a.section_code = b.section_code 
            AND a.course_number = b.course_number 
            AND a.dept_code = b.dept_code 
        INNER JOIN Book c 
          ON b.isbn = c.isbn;
          
CREATE VIEW all_book_data 
	AS SELECT a.*, 
	b.term_number, b.section_code, b.course_number, b.dept_code, 
	d.family_name, d.given_name,
	c.id_number
	FROM Book a LEFT JOIN Requires b ON a.isbn = b.isbn 
				LEFT JOIN Written d ON a.isbn = d.isbn
				LEFT JOIN Enrolled c ON b.term_number = c.term_number 
					AND b.dept_code = c.dept_code 
					AND b.section_code = c.section_code 
					AND b.course_number = c.course_number 
	ORDER By isbn;
          
CREATE VIEW student_req_written
	AS SELECT a.*, b.family_name, b.given_name FROM student_req_books a
	LEFT JOIN Written b ON a.isbn = b.isbn;
          
CREATE VIEW written_book_data
	AS SELECT a.*, b.family_name, b.given_name FROM Book a LEFT JOIN Written b ON a.isbn = b.isbn;
		
CREATE VIEW written_req_book
    AS SELECT a.*, b.term_number, b.section_code, b.course_number, b.dept_code 
    FROM written_book_data a
    INNER JOIN Requires b ON a.isbn = b.isbn;

CREATE VIEW required_book_data
	AS SELECT a.*, 
	b.term_number, b.section_code, b.course_number, b.dept_code FROM Book a INNER JOIN Requires b ON a.isbn = b.isbn;

/*
 * Add permissions to the user guest if it does not exist
 * Mysql will create the user.
 */
GRANT SELECT
    ON bookstore.Author
    TO 'guest'@'localhost' IDENTIFIED BY 'guestaccount';

GRANT SELECT
    ON bookstore.Book
    TO 'guest'@'localhost';

GRANT SELECT
    ON bookstore.BookPublisher
    TO 'guest'@'localhost';

GRANT SELECT,INSERT 
    ON bookstore.Bookorder
    TO 'guest'@'localhost';

GRANT SELECT
    ON bookstore.Bookstore
        TO 'guest'@'localhost';

GRANT SELECT, INSERT
    ON bookstore.Contains
    TO 'guest'@'localhost';

GRANT SELECT
    ON bookstore.Course
    TO 'guest'@'localhost';

GRANT SELECT
    ON bookstore.Department
    TO 'guest'@'localhost';

GRANT SELECT
    ON bookstore.Enrolled
    TO 'guest'@'localhost';

GRANT SELECT
    ON bookstore.Phone
    TO 'guest'@'localhost';

GRANT SELECT
    ON bookstore.Publisher
    TO 'guest'@'localhost';

GRANT SELECT
    ON bookstore.Requires
    TO 'guest'@'localhost';

GRANT SELECT
    ON bookstore.Section
    TO 'guest'@'localhost';

GRANT SELECT
    ON bookstore.Stocks
    TO 'guest'@'localhost';

GRANT SELECT
    ON bookstore.Written
    TO 'guest'@'localhost';

GRANT SELECT
    ON bookstore.order_view
    TO 'guest'@'localhost';
  
GRANT SELECT
	ON bookstore.student_req_books
	TO 'guest'@'localhost';
	
GRANT SELECT
	ON bookstore.written_book_data
	TO 'guest'@'localhost';

GRANT SELECT
	ON bookstore.student_req_written
	TO 'guest'@'localhost';
	
GRANT SELECT
	ON bookstore.all_book_data
	TO 'guest'@'localhost';

GRANT SELECT
    ON bookstore.written_req_book
    TO 'guest'@'localhost';


/* 
 * ADD EMPLOYEE ACCOUNT 
 * Grant permissions to Employee accounts.
 */       
GRANT SELECT,INSERT,UPDATE,CREATE
    ON bookstore.*
    TO 'storeadmin'@'localhost' IDENTIFIED BY 'adminaccount';

    
/* 
 * INSERT DATA INTO TABLES 
 */    
  

/*
 * Load departments into Department Table.
 */
LOAD DATA LOCAL INFILE "./load_files/dept.txt"
INTO TABLE Department
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';

/*
 * Load courses into Course Table.
 */
LOAD DATA LOCAL INFILE "./load_files/course.txt"
INTO TABLE Course
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';

/*
 * Load publishers into Publisher Table.
 */
LOAD DATA LOCAL INFILE "./load_files/publish.txt"
INTO TABLE Publisher
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';


/*
 * Load students into Student Table.
 */
LOAD DATA LOCAL INFILE "./load_files/student.txt"
INTO TABLE Student
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';

/*
 * Load sections into Section Table.
 */
LOAD DATA LOCAL INFILE "./load_files/section.txt"
INTO TABLE Section
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';


/*
 * Load books into Books Table.
 */
LOAD DATA LOCAL INFILE "./load_files/book.txt"
INTO TABLE Book
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';

/*
 * Load required books into Required Table.
 */
LOAD DATA LOCAL INFILE "./load_files/require.txt"
INTO TABLE Requires
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';

/*
 * Load students enrolled into Enrolled Table.
 */
LOAD DATA LOCAL INFILE "./load_files/enrolled.txt"
INTO TABLE Enrolled
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';


/*
 * Load stores into Bookstore Table.
 */
LOAD DATA LOCAL INFILE "./load_files/store.txt"
INTO TABLE Bookstore
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';

/*
 * Load orders into Bookorder Table.
 */
LOAD DATA LOCAL INFILE "./load_files/order.txt"
INTO TABLE Bookorder
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';

/*
 * Load books from order into contains Table.
 */
LOAD DATA LOCAL INFILE "./load_files/contains.txt"
INTO TABLE Contains
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';

/*
 * Load books and quantities into Stocks Table.
 */
LOAD DATA LOCAL INFILE "./load_files/stocks.txt"
INTO TABLE Stocks
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';

/*
 * Load phone numbers into Phone Table.
 */
LOAD DATA LOCAL INFILE "./load_files/phone.txt"
INTO TABLE Phone
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';

/*
 * Load book publishers into BookPublisher Table.
*/
LOAD DATA LOCAL INFILE "./load_files/bp.txt"
INTO TABLE BookPublisher
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';

/*
 * Load author names into Author Table.
 */
LOAD DATA LOCAL INFILE "./load_files/author.txt"
INTO TABLE Author
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';

/*
 * Load books written by authors into written Table.
 */
LOAD DATA LOCAL INFILE "./load_files/write.txt"
INTO TABLE Written
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';


/*
 * Load employees into Employee Table.
 */
LOAD DATA LOCAL INFILE "./load_files/employee.txt"
INTO TABLE Employee
COLUMNS TERMINATED BY ','
LINES TERMINATED BY '\n'
  (employee_id, @var1)
  SET password_hash = PASSWORD(@var1);


/*
 * Load employees work places into EmplyeeList Table.
 */
LOAD DATA LOCAL INFILE "./load_files/employlist.txt"
INTO TABLE EmployeeList
COLUMNS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
ESCAPED BY '"'
LINES TERMINATED BY '\n';

