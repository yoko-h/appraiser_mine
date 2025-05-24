<?php

//
// タイトル管理画面
//
function subFTitle()
{
  $param = getFTitleParam();

  // if ($param["sDel"] == '') {
  if (!isset($param["sDel"]) || !$param["sDel"]) {
    $param["sDel"] = 1;
  }

  if (!$param["orderBy"]) {
    $param["orderBy"] = 'CLASSNO';
    $param["orderTo"] = 'asc';
  }

  subMenu();
  subFTitleView($param);
}

//
// 項目管理画面
//
function subFTitleItem()
{
  $param = getFTitleParam();
  // if ($param["sDel"] == '') {
  if (!isset($param["sDel"]) || !$param["sDel"]) {
    $param["sDel"] = 1;
  }

  if (! $param["orderBy"]) {
    $param["orderBy"] = 'SEQNO';
    $param["orderTo"] = 'asc';
  }
  $param["sClassNo"] = htmlspecialchars($_REQUEST['sClassNo']);
  $param["sDocNo"] = htmlspecialchars($_REQUEST['sDocNo']);

  subMenu();
  subFTitleItemView($param);
}

//
// タイトル管理編集画面
//
function subFTitleEdit()
{
  $param = getFTitleParam();
  $param["DocNo"] = htmlspecialchars($_REQUEST['docNo']);

  if ($param["DocNo"]) { //更新
    echo "<br>logic:DocNoあり<br>";
    $sql = fnSqlFTitleEdit($param["DocNo"]);
    $res = mysqli_query($param["conn"], $sql);
    $row = mysqli_fetch_array($res);

    $param["DocNo"] = htmlspecialchars($row[0]);
    $param["classNo"] = htmlspecialchars($row[1]);
    $param["seqNo"] = htmlspecialchars($row[2]);
    $param["name"] = htmlspecialchars($row[3]);

    $param["purpose"] = '更新';
    $param["btnImage"] = 'btn_load.png';
  } else { //登録
    $param["purpose"] = '登録';
    $param["btnImage"] = 'btn_enter.png';
  }

  subMenu();
  // if ($param["sDocNo"]) {
  //   subFTitleItemEditView($param);
  // } else {
  subFTitleEditView($param);
  // }
}
//
// 項目名管理編集画面
//
function subFTitleItemEdit()
{
  $param = getFTitleParam();
  $param["DocNo"] = htmlspecialchars($_REQUEST['docNo']);
  $param["sDocNo"] = htmlspecialchars($_REQUEST['sDocNo']);
  $param["sClassNo"] = htmlspecialchars($_REQUEST['sClassNo']);

  if ($param["DocNo"]) {
    echo 'DocNoあり';
    $sql = fnSqlFTitleEdit($param["DocNo"]);
    $res = mysqli_query($param["conn"], $sql);
    $row = mysqli_fetch_array($res);

    $param["DocNo"] = htmlspecialchars($row[0] ?? '');
    $param["classNo"] = htmlspecialchars($row[1] ?? '');
    $param["seqNo"] = htmlspecialchars($row[2] ?? '');
    $param["name"] = htmlspecialchars($row[3] ?? '');

    $param["purpose"] = '更新';
    $param["btnImage"] = 'btn_load.png';
  } else {
    echo 'DocNoなし';
    $param["purpose"] = '登録';
    $param["btnImage"] = 'btn_enter.png';
  }

  subMenu();
  echo "<br>↓logic:項目管理編集画面 subFTitleItemEdit<br>";
  subFTitleItemEditView($param);
  echo "<br>↑logic:項目管理編集画面 subFTitleItemEdit<br>";
}
//
// タイトル管理編集完了処理
//
function subFTitleEditComplete()
{
  $param = getFTitleParam();
  echo "<br>↓logic:完了処理 subFTitleEditComplete<br>";
  $param["DocNo"] = mysqli_real_escape_string($param["conn"], $_REQUEST['DocNo'] ?? '');
  $param["classNo"] = mysqli_real_escape_string($param["conn"], $_REQUEST['classNo']);
  $param["seqNo"] = mysqli_real_escape_string($param["conn"], $_REQUEST['seqNo']);
  $param["name"] = mysqli_real_escape_string($param["conn"], $_REQUEST['name']);
  $param["sClassNo"] = mysqli_real_escape_string($param["conn"], $_REQUEST['sClassNo'] ?? '');

  $ErrClassNo = subFTitleRepetition($param["classNo"], $param["DocNo"], $param["seqNo"]);

  if ($param["DocNo"]) {
    echo '<br>DocNoあり<br>';
    if ($param["seqNo"] === '0') {
      echo '<br>seqNoが0<br>';
      echo $param["seqNo"];
      // タイトルの更新処理
      if (! $ErrClassNo) {
        echo '<br>エラーじゃない時<br>';
        // 更新前の情報を取得
        $sql = fnSqlFTitleEdit($param["DocNo"]);
        $res = mysqli_query($param["conn"], $sql);
        $row = mysqli_fetch_array($res);

        $beforeClassNo = htmlspecialchars($row[1]);

        // タイトルの更新
        $sql = fnSqlFTitleUpdate($param);
        $res = mysqli_query($param["conn"], $sql);

        // 紐付く項目を取得
        $sql = fnSqlFTitleRepetition($beforeClassNo);
        $result = mysqli_query($param["conn"], $sql);
        echo '<br>紐付く項目を取得$sql ';

        while ($row = mysqli_fetch_array($result)) {
          if ($row['SEQNO'] !== '0') {
            $param["DocNo"] = $row['DOCNO'];
            $param["classNo"] = $row['CLASSQNO'];
            $param["seqNo"] = $row['SEQNO'];
            $param["name"] = $row["NAME"];
            // $sql = fnSqlFTitleItemUpdate($param);
            $res = mysqli_query($param["conn"], $sql);
          }
        }
        subTitlePage0();
      } else {
        // 重複時
        echo '<br>重複時<br>';
        $param["purpose"] = '更新';
        $param["btnImage"] = 'btn_load.png';
        subFTitleMsg($param);
      }
    } else {
      echo '<br>seqNoが0じゃない<br>';
      $sql = fnSqlFTitleUpdate($param);
      $res = mysqli_query($param["conn"], $sql);
      subTitlePage1();
    }
  } else {
    echo '<br>DocNoなし<br>';
    print_r($param["seqNo"]);
    $param["DocNo"] = fnNextNo('DOC');
    if (! $param["seqNo"]) {

      if (! $ErrClassNo) {
        $param["seqNo"] = 0;
        $sql = fnSqlFTitleInsert($param);
        $res = mysqli_query($param["conn"], $sql);
        $param["ttl_flg"] = 0;
      } else {
        $param["DocNo"] = "";
        $param["purpose"] = '登録';
        $param["btnImage"] = 'btn_enter.png';
        subFTitleMsg($param);
      }
      subTitlePage0();
    } else {
      $sql = fnSqlFTitleInsert($param);
      $res = mysqli_query($param["conn"], $sql);
      subTitlePage1();
    }
  }
  echo "<br>↑logic:完了処理 subFTitleEditComplete<br>";
}

//
// 画面振り分け
//
function subTitlePage0()
{
  $_REQUEST['act'] = 'fTitleSearch';
  subFTitle();
}

function subTitlePage1()
{
  $_REQUEST['act'] = 'fTitleItemSearch';
  subFTitleItem();
}

//
// タイトル管理削除処理
//
function subFTitleDelete($classNo, $docNo, $seqNo)
{
  $conn = fnDbConnect();

  $DocNo = $_REQUEST['DocNo'];

  if ($_REQUEST['seqNo'] == 0) {
    $sql = fnSqlFTitleRepetition($classNo, $docNo, $seqNo);
    $res = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($res)) {
      $sql = fnSqlFTitleDelete($row['DOCNO']);
      $res = mysqli_query($conn, $sql);
    }
  } else {
    $sql = fnSqlFTitleDelete($DocNo);
    $res = mysqli_query($conn, $sql);
  }

  $_REQUEST['act'] = 'fTitleSearch';
  subFTitle();
}

//
// 画面間引継ぎ情報(準備)
//
function getFTitleParam()
{
  $param = array();

  // DB接続
  $param["conn"] = fnDbConnect();

  $param["orderBy"] = $_REQUEST['orderBy'] ?? '';
  $param["orderTo"] = $_REQUEST['orderTo'] ?? '';

  return $param;
}



//
// 重複チェック
//
function subFTitleRepetition($classNo, $docNo, $seqNo)
{
  $conn = fnDbConnect();

  $sql = fnSqlFTitleRepetition($classNo, $docNo, $seqNo);
  $res = mysqli_query($conn, $sql);
  while ($row = mysqli_fetch_array($res)) {
    if ($row['CLASSNO'] !== $classNo && $row['SEQNO'] == '0') { //同じCLASSNOタイトルが存在してないか？
      if ($row['DOCNO'] !== $docNo) { //管理Noが既に存在してないか？
        return $row['CLASSNO'];
      }
    } else if ($row['CLASSNO'] == $classNo && $row['SEQNO'] < '0') { //同じCLASSNOタイトルが存在してないか？
      if ($row['DOCNO'] !== $docNo) { //管理Noが既に存在してないか？
        return $row['CLASSNO'];
      }
    }
  }
  echo "<br>logic 重複チェック";
}

//
// エラー表示
//
function subFTitleMsg($param)
{
  $param["classNoChk"] = "既に登録されている表示順です";
  $sql = fnSqlFTitleEdit($param["DocNo"]);
  $res = mysqli_query($param["conn"], $sql);
  // $param["classNo"] = mysqli_result( $res,0,1 );
  $row = mysqli_fetch_array($res);
  $param["classNo"] = $row[1];

  $_REQUEST['act'] = 'fTitleEdit';
  subMenu();
  subFTitleEditView($param);
  print "</body>\n</html>";
  exit();
  // var_dump('エラー表示');
}
