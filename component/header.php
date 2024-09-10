<?php
function active($page)
{
  $url_array = explode('/', $_SERVER['REQUEST_URI']);
  $url = end($url_array);
  if ($page == $url) {
    return TRUE;
  }
  return FALSE;
}

function activeLinkContains($keyword)
{
  if (strpos($_SERVER['REQUEST_URI'], $keyword) !== false) {
    return TRUE;
  }
  return FALSE;
}
?>

<header>
  <div class="h">
    <a class="logo" href="/pd-finalProject"><img src="/pd-finalProject/images/brand.jpg" /></a>
    <nav>
      <div class="menu-nav">
        <ul>
          <li><a style="<?php if (activeLinkContains('home') or active('schedule.php'))
            echo "color: dodgerblue; font-weight:bold;" ?>" href="/pd-finalProject/home">Home</a></li>
            <li><a style="<?php if (active('about.php'))
            echo "color: dodgerblue; font-weight:bold;" ?>" href="/pd-finalProject/home">About</a></li>
            <li><a style="<?php if (active('contact.php'))
            echo "color: dodgerblue; font-weight:bold;" ?>" href="/pd-finalProject/contact.php">Contact</a></li>
            <li><a style="<?php if (active('gallery.php'))
            echo "color: dodgerblue; font-weight:bold;" ?>" href="/pd-finalProject/home">Gallery</a></li>
            <li><a style="<?php if (active('news.php'))
            echo "color: dodgerblue; font-weight:bold;" ?>" href="/pd-finalProject/home">News</a></li>
          </ul>
        </div>
      </nav>
    </div>

    <?php
          $path = $_SERVER['DOCUMENT_ROOT'];
          $path .= "/pd-finalProject/config.php";

          include $path;
          $sessionEmail = isset($_SESSION['SESSION_EMAIL']) ? $_SESSION['SESSION_EMAIL'] : FALSE;

          $query = mysqli_query($conn, "SELECT * FROM users WHERE email='{$sessionEmail}'");

          if (mysqli_num_rows($query) == 1) {
            echo "<a class='logout' href='/pd-finalProject/logout.php'>Logout</a>";
          } else {
            echo "<a style='cursor:pointer;' href='/pd-finalProject/login.php'>Login</a>";
          }
          ?>
</header>