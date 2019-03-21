#FRC Scout

Combining the excitement of sport with the rigors of science and technology, FIRST Robotics Competition is the ultimate Sport for the Mind. High-school student participants call it “the hardest fun you’ll ever have.”

Under strict rules, limited resources, and an intense six-week time limit, teams of students are challenged to raise funds, design a team "brand," hone teamwork skills, and build and program industrial-size robots to play a difficult field game against like-minded competitors. It’s as close to real-world engineering as a student can get. Volunteer professional mentors lend their time and talents to guide each team. Each season ends with an exciting FIRST Championship.

Information is critical. FRC Scout is an application that improves upon the process of gathering information from other teams, or "scouting". Users on the mobile side are able to meet with other teams on the fly and input it all on the mobile app. Data can then be submitted to the web server where it becomes available for everyone to view.

##Getting Started
If you have no already viewed it, see the [FRCScout Android App](https://github.com/AlphaDevelopmentSolutions/FRCScout) for Android documentation.

####Required Server Packages
This application requires the following server packages:

    mysql-client mysql-server php php-curl

Along with your choice of web hosting package.

####Configure MySQL Database
Create a new schema and write down the name of it.

After selecting the version of the instance you will be installing, navigate to:

    databases/
    
and select the matching vX.X.X.sql file to import to MySQL.

##Installation
Once you have uploaded all your files to your web server and setup the database, navigate to
 
    http(s)://(SERVER_IP_OR_DNS)/install.php
    
You will be shown a screen to input all your credentials for your web site. Once installation is complete this page will be inaccessible.

The following is a description of each field:

    App Name: Application Name to be shown on main page
    Team Number: Your team number
    
    Full URL: Full server URL including http(s)
    MySQL Database Name: Database created from "Configure MySQL Database"
    MySQL Username: MySQL user used to read / write to database. Ensure they have proper privileges
    MySQL Password: Password for the above user
    
    Blue Alliance API Key: Your blue alliance API key
    Custom API Key: API key used for communication between Android App and web server
    
After hitting save the server will generate
 
    classes/Keys.php 
    
If at any time you need to need to change server information, navigate to that file and change the values.

##Cron
For getting events and teams at those events, the web app runs off of scripts that can be cron tasked. Each script is located at
    
        cron/GetEvents.php
        cron/GetTeams.php
        
You can either run these manually or automate it with use of an OS cron.

##Error Codes
**5x01:** PHP tried to write a file to the server and failed. Does your server have correct permissions?

**5x02:** cURL failed to function. Have you installed php-curl?









