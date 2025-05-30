<?php

//
// タイトル管理画面
//
function subFTitle()
{
  $param = getFTitleParam();

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
  if (!isset($param["sDel"]) || !$param["sDel"]) {
    $param["sDel"] = 1;
  }

  if (! $param["orderBy"]) {
    $param["orderBy"] = 'CLASSNO';
    $param["orderTo"] = 'asc';
  }
  $param["sDocNo"] = htmlspecialchars($_REQUEST['sDocNo']);
  $param["sClassNo"] = htmlspecialchars($_REQUEST['sClassNo']);

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
  // $param["seqNo"] = htmlspecialchars($_REQUEST['seqNo']);

  if ($param["DocNo"]) {  //編集対象のレコードの情報
    $sql = fnSqlFTitleEdit($param["DocNo"]);
    $res = mysqli_query($param["conn"], $sql);
    $row = mysqli_fetch_array($res);

    $param["DocNo"] = htmlspecialchars($row[0]);
    $param["classNo"] = htmlspecialchars($row[1]);
    $param["seqNo"] = htmlspecialchars($row[2]);
    $param["name"] = htmlspecialchars($row[3]);

    $param["purpose"] = '更新';
    $param["btnImage"] = 'btn_load.png';
  } else {
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
  if (!isset($param["sDel"]) || !$param["sDel"]) {
    $param["sDel"] = 1;
  }

  if (! $param["orderBy"]) {
    $param["orderBy"] = 'SEQNO';
    $param["orderTo"] = 'asc';
  }

  $param["DocNo"] = htmlspecialchars($_REQUEST['docNo']);
  $param["sDocNo"] = htmlspecialchars($_REQUEST['sDocNo']); //親要素タイトルに必要
  $param["sClassNo"] = htmlspecialchars($_REQUEST['sClassNo']); //項目リストに必要
  $param["classNo"] = htmlspecialchars($_REQUEST['sClassNo']); // ここで classNo をセット

  if ($param["DocNo"]) { //編集対象のレコードの情報
    $sql = fnSqlFTitleEdit($param["DocNo"]);
    $res = mysqli_query($param["conn"], $sql);
    $row = mysqli_fetch_array($res);

    $param["DocNo"] = htmlspecialchars($row[0]);
    $param["classNo"] = htmlspecialchars($row[1]);
    $param["seqNo"] = htmlspecialchars($row[2]);
    $param["name"] = htmlspecialchars($row[3]);

    $param["purpose"] = '更新';
    $param["btnImage"] = 'btn_load.png';
  } else {
    $param["purpose"] = '登録';
    $param["btnImage"] = 'btn_enter.png';
  }

  subMenu();
  subFTitleItemEditView($param);
}

//
// 編集完了処理
//
function subFTitleEditComplete()
{
  $param = getFTitleParam();

  $param["sDocNo"] = mysqli_real_escape_string($param["conn"], $_REQUEST['sDocNo'] ?? '');
  $param["DocNo"] = mysqli_real_escape_string($param["conn"], $_REQUEST['docNo'] ?? '');
  $param["classNo"] = mysqli_real_escape_string($param["conn"], $_REQUEST['classNo'] ?? '');
  $param["seqNo"] = mysqli_real_escape_string($param["conn"], $_REQUEST['seqNo'] ?? '');
  $param["name"] = mysqli_real_escape_string($param["conn"], $_REQUEST['name'] ?? '');

  // 更新前の情報を取得
  $sqlBefore = fnSqlFTitleEdit($param["DocNo"]);
  $resBefore = mysqli_query($param["conn"], $sqlBefore);
  $rowBefore = mysqli_fetch_array($resBefore);
  $beforeClassNo = htmlspecialchars($rowBefore['CLASSNO'] ?? '');
  $beforeName = htmlspecialchars($rowBefore['NAME'] ?? '');

  $haveParent = $param["seqNo"] > 0 || $param["classNo"] === ''; // 親要素でない定義(親要素あり)
  $ErrClassNo = subFTitleRepetition($param["classNo"], $param["DocNo"], $param["seqNo"], false); //タイトル登録で使う
  if ($param["DocNo"]) { //更新コード
    if ($param["seqNo"] == '0' && !$haveParent || $param["seqNo"] == '') { //---タイトル更新
      $haveParent = false; // タイトル更新時は親要素
      // ★ 変更がないかチェック
      if ($param["classNo"] == $beforeClassNo && $param["name"] == $beforeName) {
        subTitlePage0();
        return;
      }
      if (!$ErrClassNo) {
        // タイトルの更新
        $sql = fnSqlFTitleUpdate($param);
        $res = mysqli_query($param["conn"], $sql);

        // 紐付く項目を取得
        $sql = fnSqlFTitleItemList($beforeClassNo); // fnSqlFTitleItemList に $beforeClassNo を渡す
        $result = mysqli_query($param["conn"], $sql);
        while ($row = mysqli_fetch_array($result)) {
          $childDocNo = $row['DOCNO'];
          $newClassNo = $param["classNo"];
          $childSeqNo = $row['SEQNO'];
          $childName = $row['NAME'];
          if ($childSeqNo > 0) {
            $updateSql = fnSqlFTitleItemUpdate(array(
              "DocNo" => $childDocNo,
              "classNo" => $newClassNo,
              "seqNo" => $childSeqNo,
              "name" => $childName
            ));
            $updateResult = mysqli_query($param["conn"], $updateSql);
            if (!$updateResult) {
              echo "<br>Error updating child DOCNO " . $childDocNo . ": " . mysqli_error($param["conn"]) . "<br>";
            }
          }
        }
        subTitlePage0();
      } else {
        // 重複時
        $param["purpose"] = '更新';
        $param["btnImage"] = 'btn_load.png';
        subFTitleMsg($param, 'classNoDup'); //エラータイプを渡す
      }
    } else {                      //---項目更新
      $ErrClassNo = subFTitleRepetition($param["classNo"], $param["DocNo"], $param["seqNo"], $haveParent);
      if (!$ErrClassNo) {
        $sql = fnSqlFTitleItemUpdate($param);
        $res = mysqli_query($param["conn"], $sql);
        subTitlePage1();
      } else {
        $param["purpose"] = '更新';
        $param["btnImage"] = 'btn_load.png';
        subFTitleMsg($param, 'seqNoDup'); //エラータイプを渡す
      }
    }
  } else { //登録コード
    $param["DocNo"] = fnNextNo('DOC');

    if (!$haveParent) {              //---タイトル登録 親要素なし
      if (!$ErrClassNo) {
        $param["seqNo"] = 0;
        $sql = fnSqlFTitleInsert($param);
        $res = mysqli_query($param["conn"], $sql);
        subTitlePage0();
      } else {
        $param["purpose"] = '登録';
        $param["btnImage"] = 'btn_enter.png';
        subFTitleMsg($param, 'classNoDup'); //エラータイプを渡す
      }
    } else {                      //---項目登録 親要素あり
      $ErrClassNo = subFTitleRepetition($param["classNo"], $param["DocNo"], $param["seqNo"], $haveParent);
      if (!$ErrClassNo) {
        $sql = fnSqlFTitleInsert($param);
        $res = mysqli_query($param["conn"], $sql);
        subTitlePage1();
      } else {
        // $param["DocNo"] = ""; // ★これが原因でエラーが起きてた
        $param["purpose"] = '登録';
        $param["btnImage"] = 'btn_enter.png';
        subFTitleMsg($param, 'seqNoDup'); //エラータイプを渡す
      }
    }
  }
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
// 削除処理
//
function subFTitleDelete($classNo, $docNo, $seqNo)
{
  $conn = fnDbConnect();

  $docNo = $_REQUEST['DocNo'];
  echo "<br>削除処理<br>";
  if ($_REQUEST['seqNo'] == 0) {
    $sql = fnSqlFTitleRepetitionParent($classNo, $seqNo, $docNo);
    $res = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($res)) {
      $sql = fnSqlFTitleDelete($row['DOCNO']);
      $res = mysqli_query($conn, $sql);
    }
  } else {
    $sql = fnSqlFTitleDelete($docNo);
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
function subFTitleRepetition($classNo, $docNo, $seqNo, $haveParent)
{
  $conn = fnDbConnect();
  $sql = '';

  if ($haveParent) {
    $sql = fnSqlFTitleRepetitionParent($classNo, $seqNo, $docNo);
  } else {
    $sql = fnSqlFTitleRepetitionChild($classNo, $seqNo, $docNo);
  }

  $res = mysqli_query($conn, $sql);
  if ($res) {
    if (mysqli_num_rows($res) > 0) {
      return true; // 重複あり
    }
  }
  return false; // 重複なし
}

//
// エラー表示
//
function subFTitleMsg($param, $errorType)
{
  $param["DocNo"] = ''; // DocNo をクリア
  if ($errorType === 'classNoDup') { //タイトルでのエラー
    $param["classNoChk"] = "既に登録されている表示順です";
    subMenu();
    subFTitleEditView($param);
  } elseif ($errorType === 'seqNoDup') { //項目でのエラー
    $param["seqNoChk"] = "既に登録されている表示順です";
    subMenu();
    subFTitleItemEditView($param);
  }
  print "</body>\n</html>";
  exit();
}
