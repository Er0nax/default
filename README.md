<h2>Welcome to the default website template by eronax!</h2>

<br>

<h4>How to set up files:</h4>
<ul>
    <li>
        Copy everything to a <b>new folder</b>. You might consider checking for a new bootstrap/jQuery version.
    </li>
    <li>
        Change all important variables inside the .env file. All variables which already exist in there <b>should not be deleted</b>.
    </li>
    <li>
        New variables added inside the .env file are accessable everything with <b>$app->env->variables['key']</b>
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
</ul>

<h4>How to set up image paths</h4>
<ul>
    <li>
        Go into <b>app/helpers/siteHelper</b> to getImage(). There you can add custom image folders with a custom image name for a fallback.
    </li>
</ul>
