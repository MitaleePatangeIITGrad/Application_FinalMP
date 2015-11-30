# Application_FinalMP

This repository contains all the php, html and jquery related files and folders.

User visible front end files -

Index.html - 
> This is the index html page (default for the application) which displays an IIT grad picture from aws s3 bucket and a link to go to the second html page.

Page2.html - 
> This is a second html page which displays the IIT hawk image.

Index.php - 
> This is an index page for the images application where the user needs to fill in the phone number to login or signup (if a first time user) to the application. 
> This page also gives the user the flexibility to view the gallery page without signup or login to view all raw images posted by all users of the application.
> There is a backup database link which is useful for the IT/Admin to take a backup of the database and which provides an option for them to disable uploads to the application (Read-only mode)

Signup.php - 
> This page has a form to obtain details from the user (Name, Phone, Email and Subscription preference)

Upload.php - 
> Depending on the subscription details, user would be shown a message and the image can be uploaded for the user specific account

Gallery.php - 
> This page displays the images per user from the readonly database MySQL - (both original and sketch) and uses jQuery to display it in a form of carousel and slideshow.
> If the user hasn't signed or logged in, all the images (original- raw ones) will be displayed.

Introspection.php -
> This page is displayed in case the IT admin wants to take up a backup of database and enable/disable readonly mdde for the web application. It gives details log of the backup.

Backend or debugging files -

Login.php - 
> This page logic gets the phone number matching in the database and corresponding details; if not - redirects to the signup page.

Insert.php - 
> This page will process the insertion of details of the signed up user into the user table in database and send a subscription message to the user in case the preference is 'Yes' to get subscribed to the topic ; redirects users to upload page to upload the image.

Submit.php - 
> This is an intermediate page for doing all the procedure of connecting to database, creating s3 buckets, inserting the images (both raw and finished) in the bucket and data (including the aws s3 link for images) in the database. The finished url is the sketch of the uploaded image created by php imagick. This page also deals with publishing a message to the subscribers of the topic.Bucket Life cycle configuration is also done with an expiry date.

Readonly.php - 
> This page connects to the readonly database and inserts or updates the mode (Readonly=Y or N) of the website as per the preference in the introspection page.

Logout.php - 
> Destroys the session and all its related variables.

Header.php - 
> Header of the application which displays website name and links to browse to gallery, upload image and logout once the phone number is entered by the user.

Footer.php - 
> Copyright footer of the website.

JQuery folders - 
> css,js and img - All the scripts, css forms and images required for the jquery blueImp plugin
