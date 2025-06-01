<?php
//
//工事管理リスト
//
function fnSqlConstList($flg, $sDel, $sArticle, $sConstTrader, $sConstFlg1, $sConstFlg2, $sConstFlg3, $sConstFlg4, $sInteriorCharge, $sPage, $orderBy, $orderTo)
{
  switch ($flg) {
    case 0:
      $sql  = "SELECT COUNT(*)";
      break;
    case 1:
      $sql  = "SELECT ARTICLENO, ARTICLE, ROOM, ADDRESS, CONSTTRADER, CONSTADD, RECEIVE, SELLCHARGE, INTERIORCHARGE, KEYPLACE, LIGHTORDER,"
        . "IF( INTERIORPRICE > 0, INTERIORPRICE, '' ) AS INTERIORPRICE,"
        . "IF( CONSTPRICE > 0, CONSTPRICE, '' ) AS CONSTPRICE,"
        . "IF( STR_TO_DATE(PURCHASEDT, '%Y-%m-%d') IS NOT NULL, DATE_FORMAT(PURCHASEDT, '%Y/%m/%d'), '' ) AS PURCHASEDT,"
        . "IF( STR_TO_DATE(WORKSTARTDT, '%Y-%m-%d') IS NOT NULL, DATE_FORMAT(WORKSTARTDT, '%Y/%m/%d'), '' ) AS WORKSTARTDT,"
        . "IF( STR_TO_DATE(WORKENDDT, '%Y-%m-%d') IS NOT NULL, DATE_FORMAT(WORKENDDT, '%Y/%m/%d'), '' ) AS WORKENDDT,"
        . "IF( STR_TO_DATE(LINEOPENDT, '%Y-%m-%d') IS NOT NULL, DATE_FORMAT(LINEOPENDT, '%Y/%m/%d'), '' ) AS LINEOPENDT,"
        . "IF( STR_TO_DATE(LINECLOSEDT,'%Y-%m-%d') IS NOT NULL, DATE_FORMAT(LINECLOSEDT, '%Y/%m/%d'),'') AS LINECLOSEDT,"
        . "IF( STR_TO_DATE(SITEDT, '%Y-%m-%d') IS NOT NULL, DATE_FORMAT(SITEDT, '%Y/%m/%d'), '' ) AS SITEDT,"
        . "IF( STR_TO_DATE(LINEOPENCONTACTDT, '%Y-%m-%d') IS NOT NULL, DATE_FORMAT(LINEOPENCONTACTDT, '%Y/%m/%d'), '' ) AS LINEOPENCONTACTDT,"
        . "IF( STR_TO_DATE(LINECLOSECONTACTDT,'%Y-%m-%d') IS NOT NULL, DATE_FORMAT(LINECLOSECONTACTDT, '%Y/%m/%d'),'') AS LINECLOSECONTACTDT";
      break;
  }

  $sql .= " FROM TBLARTICLE";
  $sql .= " WHERE DEL = $sDel";

  // 各項目の検索条件の抽出
  if ($sArticle) {
    $sql .= " AND ARTICLE LIKE '%$sArticle%'";
  }
  // if ($sRoom) {
  //   $sql .= " AND ROOM LIKE '%$sRoom%'";
  // }
  // if ($sAddress) {
  //   $sql .= " AND ADDRESS LIKE '%$sAddress%'";
  // }
  if ($sConstTrader) {
    $sql .= " AND CONSTTRADER LIKE '%$sConstTrader%'";
  }
  if ($sConstFlg1 || $sConstFlg2 || $sConstFlg3 || $sConstFlg4) {
    $sql .= " AND (";
    $tmp = "";
    if ($sConstFlg1) {
      $sql .= $tmp . "CONSTFLG1 = 1";
      $tmp = " OR ";
    }
    if ($sConstFlg2) {
      $sql .= $tmp . "CONSTFLG2 = 1";
      $tmp = " OR ";
    }
    if ($sConstFlg3) {
      $sql .= $tmp . "CONSTFLG3 = 1";
      $tmp = " OR ";
    }
    if ($sConstFlg4) {
      $sql .= $tmp . "CONSTFLG4 = 1";
    }
    $sql .= ")";
  }
  if ($sInteriorCharge) {
    $sql .= " AND INTERIORCHARGE LIKE '%$sInteriorCharge%'";
  }
  if ($orderBy) {
    $sql .= " ORDER BY $orderBy $orderTo";
  }
  if ($flg) {
    $sql .= " LIMIT " . (($sPage - 1) * PAGE_MAX) . ", " . PAGE_MAX;
  }

  return ($sql);
}



//
//工事管理情報
//
function fnSqlConstEdit($articleNo)
{
  $sql  = "SELECT ARTICLE, ROOM, ADDRESS, YEARS,"
    . "CONSTTRADER, CONSTADD, CONSTNOTE, RECEIVE, HOTWATER,"
    . "KEYPLACE, MANAGECOMPANY, FLOORPLAN, FORMEROWNER, BROKERCHARGE, BROKERCONTACT, INTERIORCHARGE,"
    . "SELLCHARGE, CONSTFLG1, CONSTFLG2, CONSTFLG3, CONSTFLG4, LEAVINGFORM,"
    . "ELECTRICITYCHARGE, GASCHARGE, LIGHTORDER, LINECONTACTNOTE,"
    . "IF( AREA > 0, AREA, '' ) AS AREA,"
    . "IF( SELLPRICE > 0, SELLPRICE, '' ) AS SELLPRICE,"
    . "IF( INTERIORPRICE > 0, INTERIORPRICE, '' ) AS INTERIORPRICE,"
    . "IF( CONSTPRICE > 0, CONSTPRICE, '') AS CONSTPRICE,"
    . "IF( STR_TO_DATE(SITEDT, '%Y-%m-%d') IS NOT NULL, DATE_FORMAT(SITEDT, '%Y/%m/%d'), '' ) AS SITEDT,"
    . "IF( STR_TO_DATE(LEAVINGDT, '%Y-%m-%d') IS NOT NULL, DATE_FORMAT(LEAVINGDT, '%Y/%m/%d'), '' ) AS LEAVINGDT,"
    . "IF( STR_TO_DATE(PURCHASEDT, '%Y-%m-%d') IS NOT NULL, DATE_FORMAT(PURCHASEDT, '%Y/%m/%d'), '' ) AS PURCHASEDT,"
    . "IF( STR_TO_DATE(WORKSTARTDT, '%Y-%m-%d') IS NOT NULL, DATE_FORMAT(WORKSTARTDT, '%Y/%m/%d'), '' ) AS WORKSTARTDT,"
    . "IF( STR_TO_DATE(WORKENDDT, '%Y-%m-%d') IS NOT NULL, DATE_FORMAT(WORKENDDT, '%Y/%m/%d'), '' ) AS WORKENDDT,"
    . "IF( STR_TO_DATE(LINEOPENDT, '%Y-%m-%d') IS NOT NULL, DATE_FORMAT(LINEOPENDT, '%Y/%m/%d'), '' ) AS LINEOPENDT,"
    . "IF( STR_TO_DATE(LINECLOSEDT,'%Y-%m-%d') IS NOT NULL, DATE_FORMAT(LINECLOSEDT, '%Y/%m/%d'),'') AS LINECLOSEDT,"
    . "IF( STR_TO_DATE(LINEOPENCONTACTDT, '%Y-%m-%d') IS NOT NULL, DATE_FORMAT(LINEOPENCONTACTDT, '%Y/%m/%d'), '' ) AS LINEOPENCONTACTDT,"
    . "IF( STR_TO_DATE(LINECLOSECONTACTDT,'%Y-%m-%d') IS NOT NULL, DATE_FORMAT(LINECLOSECONTACTDT, '%Y/%m/%d'),'') AS LINECLOSECONTACTDT";
  $sql .= " FROM TBLARTICLE";
  $sql .= " WHERE ARTICLENO = $articleNo";

  return ($sql);
}


//
//工事管理情報更新
//
function sqlValue($value)
{
  return ($value === null) ? "NULL" : "'" . $value . "'";
}
function fnSqlConstUpdate(
  $articleNo,
  $area,
  $years,
  $sellPrice,
  $interiorPrice,
  $constTrader,
  $constPrice,
  $constAdd,
  $constNote,
  $purchaseDT,
  $workStartDT,
  $workEndDT,
  $lineOpenDT,
  $lineCloseDT,
  $receive,
  $hotWater,
  $siteDT,
  $leavingForm,
  $leavingDT,
  $manageCompany,
  $floorPlan,
  $formerOwner,
  $brokerCharge,
  $brokerContact,
  $interiorCharge,
  $constFlg1,
  $constFlg2,
  $constFlg3,
  $constFlg4,
  $lineOpenContactDT,
  $lineCloseContactDT,
  $lineContactnote,
  $electricityCharge,
  $gasCharge,
  $lightOrder
) {
  $sql  = "UPDATE TBLARTICLE SET ";
  $updates = [];

  $updates[] = "AREA = " . sqlValue($area);
  $updates[] = "YEARS = " . sqlValue($years);
  $updates[] = "SELLPRICE = " . sqlValue($sellPrice);
  $updates[] = "INTERIORPRICE = " . sqlValue($interiorPrice);
  $updates[] = "CONSTTRADER = " . sqlValue($constTrader);
  $updates[] = "CONSTPRICE = " . sqlValue($constPrice);
  $updates[] = "CONSTADD = " . sqlValue($constAdd);
  $updates[] = "CONSTNOTE = " . sqlValue($constNote);
  $updates[] = "PURCHASEDT = " . sqlValue($purchaseDT);
  $updates[] = "WORKSTARTDT = " . sqlValue($workStartDT);
  $updates[] = "WORKENDDT = " . sqlValue($workEndDT);
  $updates[] = "LINEOPENDT = " . sqlValue($lineOpenDT);
  $updates[] = "LINECLOSEDT = " . sqlValue($lineCloseDT);
  $updates[] = "RECEIVE = " . sqlValue($receive);
  $updates[] = "HOTWATER = " . sqlValue($hotWater);
  $updates[] = "SITEDT = " . sqlValue($siteDT);
  $updates[] = "LEAVINGFORM = " . sqlValue($leavingForm);
  $updates[] = "LEAVINGDT = " . sqlValue($leavingDT);
  $updates[] = "MANAGECOMPANY = " . sqlValue($manageCompany);
  $updates[] = "FLOORPLAN = " . sqlValue($floorPlan);
  $updates[] = "FORMEROWNER = " . sqlValue($formerOwner);
  $updates[] = "BROKERCHARGE = " . sqlValue($brokerCharge);
  $updates[] = "BROKERCONTACT = " . sqlValue($brokerContact);
  $updates[] = "INTERIORCHARGE = " . sqlValue($interiorCharge);
  $updates[] = "CONSTFLG1 = " . sqlValue($constFlg1);
  $updates[] = "CONSTFLG2 = " . sqlValue($constFlg2);
  $updates[] = "CONSTFLG3 = " . sqlValue($constFlg3);
  $updates[] = "CONSTFLG4 = " . sqlValue($constFlg4);
  $updates[] = "LINEOPENCONTACTDT = " . sqlValue($lineOpenContactDT);
  $updates[] = "LINECLOSECONTACTDT = " . sqlValue($lineCloseContactDT);
  $updates[] = "LINECONTACTNOTE = " . sqlValue($lineContactnote);
  $updates[] = "ELECTRICITYCHARGE = " . sqlValue($electricityCharge);
  $updates[] = "GASCHARGE = " . sqlValue($gasCharge);
  $updates[] = "LIGHTORDER = " . sqlValue($lightOrder);

  $sql .= implode(",", $updates);
  $sql .= " WHERE ARTICLENO = $articleNo";

  return ($sql);
}
