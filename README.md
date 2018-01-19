# PHP Voting Application

This Polling Application is fully powered by PHP and MySQL. It models after a typical Electoral procedure where there is a registration activity carried out by an INEC official (In Nigeria). In this activity, all voters willing to partake in the use of the application for subsequent polling activities need be registered. Upon registration, a VIN (Voter's Identification Number) and password is generated for the voter for access to his/her voting portal.

The Admin (INEC official) can archive polling activities upon conclusion, register new voters, create new polling activities and view results. While a voter can access voting portal, cast votes and view results.

## Getting Started

After cloning the project to your local machine, these instructions will get the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

This guide assumes that you are already familiar with the following:

```
PHP and MySQL basics
```

```
How to connect a PHP application to a MySQL database server
```

```
Working with phpMyAdmin on a live server. If you have configured your email server on your localhost software, you can also run the application on your local server
```

```
Configuring your PHP environment using the php.ini file
```


### Installing

The first step is to create a database with the necessary tables. If you are more comfortable with MySQL commands, you can run the following commands in your terminal or type them directly in your phpMyAdmin application or just use the GUI provided by phpMyAdmin.

This is a database driven application and as such, the database tables would also be explained as well in order for you to better understand how the application works.

Create your database with your desired name, create your database user and password and grant the database user privileges to the database

```
CREATE DATABASE your_desired_name;
```

Next, is the admins table which holds the login information of all admins

```
CREATE TABLE admins (
  id int(3) NOT NULL,
  username varchar(16) NOT NULL,
  password char(32) NOT NULL,
  email_address varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

Insert login details of your choice into the table. These would be used to access the admin portal

```
INSERT INTO admins (`id`, `username`, `password`, `email_address`) VALUES
(1, 'your_desired_username', 'your_desired_password', 'your_desired_email');
```

Next, is the voters table which holds the login information of all registered voters

```
CREATE TABLE voters (
  id int(11) NOT NULL,
  identification_number char(10) DEFAULT NULL,
  password char(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

Next, is the verification table which holds the verification status (if voter has verified his/her email address) of all registered voters. The 'voter_id' column is a Foreign key to the Primary key (id) in the voters' table. Although this table is not actively used in the present state of this application, it could be utilised in the future to implement suspension/unsuspension of voter accounts.

```
CREATE TABLE verification (
  voter_id int(11) NOT NULL,
  verified tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

Next, is the biodata table which holds demographic information of each voter. Again the 'voter_id' column is a Foreign key to the Primary key (id) in the voters' table.

```
CREATE TABLE biodata (
  photo_name varchar(25) NOT NULL,
  voter_id int(11) NOT NULL,
  lastname varchar(20) NOT NULL,
  firstname varchar(20) NOT NULL,
  dob date NOT NULL,
  email_address varchar(50) NOT NULL,
  street_address varchar(50) NOT NULL,
  city varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8
```

Next, is the feedback table which holds recommendation from each registered voter on how the application can be better. Again the 'voter_id' column is a Foreign key to the Primary key (id) in the voters' table.

```
CREATE TABLE feedback (
  id int(11) NOT NULL,
  voter_id int(11) NOT NULL,
  issues text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

Next, is the vote_casted_status table which holds information about the voting status of each registered voter per polling activity. If the status is 0, it implies the voter is yet to cast vote. After a voter has casted a vote, this status is changed to 1. Also, when an admin archives all active polling activities this status is reset back to 0

```
CREATE TABLE vote_casted_status (
  voter_id int(11) NOT NULL,
  voting_status tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

Next, is the vote table which holds information about the vote category, year of vote and polling_status. The polling_status column is 0 when the voting process is still active or in progress and becomes 1 when the admin sets the polling activity to concluded. Newly created votes have a default value of 0.

```
CREATE TABLE vote (
  id int(11) NOT NULL,
  category varchar(30) NOT NULL,
  year year(4) NOT NULL,
  polling_status tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

Next, is the polls table which holds information about each candidate under each poll category (chairman, president e.t.c). The 'category_id' column is a Foreign key to the primary key (id) in the vote table.

```
CREATE TABLE `polls` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `party` varchar(25) NOT NULL,
  `photo_name` varchar(25) NOT NULL,
  `candidate_name` varchar(50) NOT NULL,
  `propaganda` tinytext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

Finally, the polls_count table which holds information about the number of votes casted per poll. The 'poll_id' is a Foreign key to the primary key (id) in the polls table.

```
CREATE TABLE `polls_count` (
  `poll_id` int(11) NOT NULL,
  `count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

In the project folder, navigate to the file 'includes/db.inc.php' and make necessary changes to the DB_USER, DB_PASS and dbname values using the values you created earlier.


## Deployment

# PHP server configurations

The following php.ini configurations are advised so as to avoid the 'cannot modify header sent...' error caused by the page redirection function used in the project. You may ask your hosting provider for assistance if you don't know how to make changes to your php.ini file.

 ```
 output_buffering = 1
 ```

Load up the index.php at localhost://www/admin on your local server or live server and login with your admin login details.


## Built With

* [PHP](php.net/manual/en/intro-whatis.php) - PHP scripting language
* [Bootstrap 3](http://www.getbootstrap.com/docs/3.3) - The Front End framework used
* [PHPMailer](https://github.com/PHPMailer) - PHP Mailing library
* [MySQL](https://www.mysql.com/) - Database Management System


## Authors

* **Chukwudi Dennis Umeilechukwu (@CupidCoder)**

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* All the wonderful authors of responses to questions asked on Stack Overflow
* My encouraging and motivating developer friends and communities
