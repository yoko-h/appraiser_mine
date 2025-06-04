<?php
//
//ログイン画面
//
function subLogin()
{
?>


  <div class="login_ttl">
    <img src="./images/logo.png">
  </div>


  <form name="form" action="index.php" method="post">
    <input type="hidden" name="act" value="loginCheck" />

    <div class="login_table">
      <table border="0" cellpadding="2" cellspacing="0">
        <tr>
          <th>ユーザーID</th>
          <td><input type="text" name="id" style="ime-mode:disabled;" autocomplete="off" /></td>
        </tr>
        <tr>
          <th>パスワード</th>
          <td><input type="password" name="pw" autocomplete="new-password" /></td>
        </tr>
      </table>
    </div>

    <div class="login_btn">
      <a href="javascript:form.submit();"><img src="./images/btn_login.png"></a>
    </div>
  </form>
<?php
}




//
//ログイン確認
//
function subLoginCheck()
{
  $id = addslashes($_REQUEST['id']);
  // $pw = addslashes($_REQUEST['pw']);
  $pw = $_REQUEST['pw']; // 平文のパスワードを取得

  $conn = fnDbConnect();

  // $sql = fnSqlLogin($id, $pw);
  $sql = "SELECT USERNO, AUTHORITY, PASSWORD FROM TBLUSER WHERE ID = '$id' AND DEL = 1"; // パスワードのハッシュ値も取得
  $res = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($res);

  // if ($row !== null && isset($row[0])) {
  if ($row !== null && isset($row['PASSWORD'])) {

    // password_verify() で入力されたパスワードとハッシュ値を比較
    if (password_verify($pw, $row['PASSWORD'])) {
      // $_COOKIE['cUserNo']   = $row[0];
      $_COOKIE['cUserNo']   = $row['USERNO'];
      // $_COOKIE['authority'] = $row[1];
      $_COOKIE['authority'] = $row['AUTHORITY'];
      $_REQUEST['act']      = 'menu';
    } else {
      $_REQUEST['act']    = 'reLogin';
    }
  } else {
    $_REQUEST['act']    = 'reLogin'; // ユーザーが見つからない
  }
}
?>
