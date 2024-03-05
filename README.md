<h2>Welcome to the default website template by eronax!</h2>

<br>

<h4>How to set up files:</h4>
<ul>
    <li>
        Copy <b>everything to a new folder</b>. You might consider checking for a new bootstrap/jQuery version.
    </li>
    <li>
        Change all important variables inside the .env file. All variables which already exist in there <b>should not be deleted</b>.
    </li>
    <li>
        New variables added inside the .env file are accessable everything with <b>$app->env->variables['key']</b>
    </li>
    <li>
        Files like "DBUSER, DBPASS, ..." will be removed through a function and are <b>not accessable</b> in any part of the code.
    </li>
</ul>

<h4>How to set up database:</h4>
<ul>
    <li>
        Create a new database with your name.
    </li>
    <li>
        Execute the sql file in <b>app/config/database.sql</b>
    </li>
    <li>
        Insert more pages you want your website to have.
    </li>
</ul>

<h4>How to set up more/custom css/js files</h4>
<ul>
    <li>
        Go into <b>app/controllers/pageController</b>. Inside the setCssFiles() and setJsFiles() function you can add your custom files.
    </li>
    <li>
        Your template js/css should be in <b>"public/assets/js" and "public/assets/css"</b>
    </li>
    <li>
        Custom js/css should be in <b>"public/assets/site/js" and "public/assets/site/css"</b>
    </li>
</ul>

<h4>How to set up image paths</h4>
<ul>
    <li>
        Go into <b>app/helpers/siteHelper</b> to getImage(). There you can add custom image folders with a custom image name for a fallback.
    </li>
</ul>

<h4>How to add more pages</h4>
<ul>
    <li>
        Create a <b>new row</b> in your database (pages table).
    </li>
    <li>
        Create a new file with the same name as in your database in <b>"public/-name-.php"</b>
    </li>
    <li>
        Create a new folder with the same name in <b>"views/content/-name-/index.view.php"</b>
    </li>
    <li>
        Create a new site module with the same name in <b>"app/modules/site/-name-.php"</b>
    </li>
</ul>
