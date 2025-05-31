<?php

//
// タイトル管理リスト (DEL=1 のみ)
//
// function fnSqlFTitleList($param)
function fnSqlFTitleList()
{
  $select = "SELECT DOCNO,CLASSNO,SEQNO,NAME";
  $from = " FROM TBLDOC";
  // $where = " WHERE DEL = 1";
  $where  = " WHERE DEL = 1 AND SEQNO = '0'";
  // $order = " ORDER BY " . $param["orderBy"] . " " . $param["orderTo"];
  $order = " ORDER BY CLASSNO ASC";
  // return $select . $from . $where . $order;
  $sql = $select . $from . $where . $order;
  return $sql;
}

//
// タイトル管理情報
//
function fnSqlFTitleEdit($docNo)
{
  $select = "SELECT DOCNO,CLASSNO,SEQNO,NAME";
  $from = " FROM TBLDOC";
  $where = " WHERE DEL = 1";
  // $where .= " AND DOCNO = $docNo";
  $where .= " AND DOCNO = '$docNo'"; // 脆弱性があり
  $sql = $select . $from . $where;
  // return $select . $from . $where;
  return $sql;
}

//
// タイトル管理情報更新
//
function fnSqlFTitleUpdate($param)
{
  $sql = "UPDATE TBLDOC SET";
  $sql .= " CLASSNO = '" . $param["classNo"] . "',";
  $sql .= " SEQNO = '" . $param["seqNo"] . "',";
  $sql .= " NAME = '" . $param["name"] . "',";
  $sql .= " UPDT = CURRENT_TIMESTAMP";
  $sql .= " WHERE DOCNO = '" . $param["DocNo"] . "'";
  return $sql;
}

//
// 項目管理リスト (特定の classNo に紐づく項目)
//
function fnSqlFTitleItemList($classNo)
{
  $select = "SELECT DOCNO, CLASSNO, SEQNO, NAME";
  $from   = " FROM TBLDOC";
  $where  = " WHERE DEL = 1 AND CLASSNO = '$classNo' AND SEQNO > 0";
  $order  = " ORDER BY SEQNO ASC"; // デフォルトのソート順
  $sql = $select . $from . $where . $order;
  return $sql;
}

//
// 項目管理情報更新
//
function fnSqlFTitleItemUpdate($param)
{
  $sql = "UPDATE TBLDOC SET";
  $sql .= " CLASSNO = '" . $param["classNo"] . "',";
  $sql .= " SEQNO = '" . $param["seqNo"] . "',";
  $sql .= " NAME = '" . $param["name"] . "',";
  $sql .= " UPDT = CURRENT_TIMESTAMP";
  $sql .= " WHERE DOCNO= " . $param["DocNo"];
  return $sql;
}

//
// タイトル管理情報登録
//
function fnSqlFTitleInsert($param)
{
  $sql = "INSERT INTO TBLDOC (";
  $sql .= "DocNo, classNo, seqNo, name, INSDT, UPDT, DEL";
  $sql .= ") VALUES (";
  $sql .= "'" . $param["DocNo"] . "', '" . $param["classNo"] . "', '" . $param["seqNo"] . "', '" . $param["name"] . "',"
    // $sql .= $param["DocNo"] . ", " . $param["classNo"] . ", " . $param["seqNo"] . ", '" . $param["name"] . "',"
    . "CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1)";
  return $sql;
}

//
// タイトル管理情報削除
//
function fnSqlFTitleDelete($docNo)
{
  echo "<br>削除クエリ<br>";
  $sql = "UPDATE TBLDOC";
  $sql .= " SET DEL = -1,";
  $sql .= " UPDT = CURRENT_TIMESTAMP";
  $sql .= " WHERE DOCNO = $docNo";
  echo "<br>↓model:削除 fnSqlFTitleDelete($docNo)<br>";
  var_dump($sql);
  return $sql;
}

//
// タイトル重複チェック用 - 親要素
//
function fnSqlFTitleRepetitionParent($classNo, $seqNo, $docNo)
{
  $select = "SELECT DOCNO, CLASSNO, SEQNO";
  $from   = " FROM TBLDOC";
  $where  = " WHERE DEL = 1 AND CLASSNO = '$classNo' AND SEQNO = '$seqNo'";
  if ($docNo) {
    $where .= " AND DOCNO <> '$docNo'"; // ★ 自身の DOCNO は除外
  }
  $sql = $select . $from . $where;
  return $sql;
}

//
// 項目重複チェック用 - 子要素
//
function fnSqlFTitleRepetitionChild($classNo, $seqNo, $docNo)
{
  $select = "SELECT DOCNO, CLASSNO, SEQNO";
  $from   = " FROM TBLDOC";
  $where  = " WHERE DEL = 1 AND CLASSNO = '$classNo' AND SEQNO = '$seqNo'";
  if ($docNo) {
    $where .= " AND DOCNO <> '$docNo'";
  }
  $sql = $select . $from . $where;
  return $sql;
}
