<?php

//
// タイトル管理リスト
//
function fnSqlFTitleList($param)
{
  $select = "SELECT DOCNO,CLASSNO,SEQNO,NAME";
  $from = " FROM TBLDOC";
  $where = " WHERE DEL = 1";
  $order = " ORDER BY " . $param["orderBy"] . " " . $param["orderTo"];
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
  // $where .= " AND DOCNO = $DocNo";
  $where .= " AND DOCNO = '$docNo'"; // 脆弱性があり
  // $where = " WHERE DEL = 1 AND DOCNO = ?"; // プレースホルダ使用

  return $select . $from . $where;
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
  $sql .= " WHERE DocNo = " . $param["DocNo"];
  // echo "<br>タイトル更新<br>";
  // var_dump($sql);
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
  $sql .= " WHERE DocNo = " . $param["DocNo"];
  // echo "<br>項目名更新<br>";
  // var_dump($sql);
  return $sql;
}

//
// タイトル管理情報登録
//
function fnSqlFTitleInsert($param)
{
  $sql = "INSERT INTO TBLDOC(";
  $sql .= "DocNo,classNo,seqNo,name,INSDT,UPDT,DEL";
  $sql .= ")VALUES(";
  // $sql .= "'" . $param["DocNo"] . "','" . $param["classNo"] . "','" . $param["seqNo"] . "','" . $param["name"] . "',"
  $sql .= "" . $param["DocNo"] . "," . $param["classNo"] . "," . $param["seqNo"] . ",'" . $param["name"] . "',"
    . "CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,1)";

  return $sql;
}

//
// タイトル管理情報削除
//
function fnSqlFTitleDelete($docNo)
{
  $sql = "UPDATE TBLDOC";
  $sql .= " SET DEL = -1";
  $sql .= ",UPDT = CURRENT_TIMESTAMP";
  $sql .= " WHERE DOCNO = '$docNo'";

  return $sql;
}

//
// タイトル項目抽出
//
function fnSqlFTitleRepetition($classNo)
{
  $select = "SELECT DOCNO,CLASSNO,SEQNO,NAME";
  $from = " FROM TBLDOC";
  $where = " WHERE DEL = 1 AND CLASSNO = '$classNo'";

  return $select . $from . $where;
}
