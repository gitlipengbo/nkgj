<?php
global $Room;
global $cards;
$id = ceil($data2['room']);
foreach ($Room[$id]['user'] as $connection3) {
	if ($connection3->user['zt'] == '1' && $connection3->user['online'] == '1') {
		$zbsl = $zbsl + 1;
	}
}
if ($data2['time'] != $Room[$id]['timexx'] || $zbsl < 2) {
	return false;
}
$Room[$id]['timexx'] = time();
$Room[$id]['xx']['zt'] = 2;
cleardjs($Room[$id]['djs'], $id);
$Room[$id]['xx']['js'] = $Room[$id]['xx']['js'] + 1;
$card = $cards;
$index = array();
foreach ($Room[$id]['user'] as $key => $connection3) {
	$fcard = [];
	if ($connection3->user['online'] != -1) {
		$msg = array();
		$msg['id'] = 'jsxx';
		$msg['html'] = $Room[$id]['xx']['js'] . '&nbsp;/&nbsp;' . $Room[$id]['xx']['zjs'] . '&nbsp;局';
		act('html', $msg, $connection3);
	}
	if ($connection3->user['zt'] == 1) {
		$index[] = array('index' => $connection3->user['index'], 'id' => $connection3->user['id']);
		$zd_line = $db->getOne('SELECT `id`, `uid`, `cards`
                     FROM `jz_user_zdcard`
                     WHERE `uid` = ' . ceil($connection3->user['id']) . ' AND `is_complete` = 0 AND `type` = "JH"
                     ORDER BY `id` ASC LIMIT 1');
		if (!empty($zd_line)) {
			$db->update('jz_user_zdcard', ['is_complete' => '1'], "`id`={$zd_line['id']}");
			$cp_card = $card;
			$fenpai = [];
			foreach (explode(',', $zd_line['cards']) as $fv) {
				foreach ($cp_card as $k => $cv) {
					if ($cv['card'] == $fv) {
						$fenpai[] = $cv;
						array_splice($cp_card, $k, 1);
						break;
					}
				}
			}
			if (count($fenpai) > 2) {
				$fcard = ['card' => $cp_card, 'fenpai' => $fenpai];
				$card = $cp_card;
			} else {
				$fcard = [];
			}
			$zd_line = null;
		}
		if (empty($fcard)) {
			$fcard = fenpai($card);
		}
		$card = $fcard['card'];
		$Room[$id]['user'][$key]->user['card'] = $fcard['fenpai'];
	}
}
$Room[$id]['card'] = $card;
$uindex = array();
$cardz = array();
foreach ($Room[$id]['user'] as $key => $value) {
	if ($value->user['zt'] == 1) {
		$Room[$id]['start'][$key] = 1;
		$card = $Room[$id]['user'][$key]->user['card'];
		$typexx = typexx($card);
		$Room[$id]['user'][$key]->user['cardmax'] = $typexx['type'] . $typexx['val'] . maxcard($card);
		$Room[$id]['user'][$key]->user['typexx'] = $typexx['type'];
		$cardz[$key] = $Room[$id]['user'][$key]->user['cardmax'];
		$user = $db->getOne("select * from jz_user where id='" . $value->user['id'] . "'");
		$uindex[$key] = preg_match('/^\s+$/', $user['disable_notice']) ? strlen($user['disable_notice']) : $user['gailv'];
	}
}
$rand = rand(0, 100);
arsort($cardz);
arsort($uindex);
$cardmax = array();
$i = 0;
foreach ($cardz as $key => $value) {
	if ($i == 0) {
		$Room[$id]['maxuser'] = $key;
		$i = 1;
	}
	$cardmax[] = array('card' => $Room[$id]['user'][$key]->user['card'], 'cardmax' => $Room[$id]['user'][$key]->user['cardmax'], 'typexx' => $Room[$id]['user'][$key]->user['typexx'], 'id' => $key);
}
$i = 0;
$hplist = array();
foreach ($uindex as $key => $value) {
	if ($value > $rand) {
		if ($i == 0) {
			$Room[$id]['maxuser'] = $key;
		}
		$hplist[$cardmax[$i]['id']]['cardmax'] = $Room[$id]['user'][$key]->user['cardmax'];
		$hplist[$cardmax[$i]['id']]['card'] = $Room[$id]['user'][$key]->user['card'];
		$hplist[$cardmax[$i]['id']]['typexx'] = $Room[$id]['user'][$key]->user['typexx'];
		$hplist[$cardmax[$i]['id']]['id'] = $Room[$id]['user'][$key]->user['id'];
		$Room[$id]['user'][$key]->user['cardmax'] = $cardmax[$i]['cardmax'];
		$Room[$id]['user'][$key]->user['typexx'] = $cardmax[$i]['typexx'];
		$Room[$id]['user'][$key]->user['card'] = $cardmax[$i]['card'];
		$i = $i + 1;
	}
}
asort($uindex);
$i = count($uindex) - 1;
foreach ($uindex as $key => $value) {
	if ($value < 0 - $rand) {
		$hplist[$cardmax[$i]['id']]['cardmax'] = $Room[$id]['user'][$key]->user['cardmax'];
		$hplist[$cardmax[$i]['id']]['card'] = $Room[$id]['user'][$key]->user['card'];
		$hplist[$cardmax[$i]['id']]['typexx'] = $Room[$id]['user'][$key]->user['typexx'];
		$hplist[$cardmax[$i]['id']]['id'] = $Room[$id]['user'][$key]->user['id'];
		$Room[$id]['user'][$key]->user['cardmax'] = $cardmax[$i]['cardmax'];
		$Room[$id]['user'][$key]->user['typexx'] = $cardmax[$i]['typexx'];
		$Room[$id]['user'][$key]->user['card'] = $cardmax[$i]['card'];
		$i = $i - 1;
	}
}
foreach ($uindex as $key => $value) {
	if ($value <= $rand && $value >= 0 - $rand) {
		if ($hplist[$key]) {
			$jhdx = $key;
			for ($i = $jhdx; $i > 0; $i) {
				if ($hplist[$hplist[$i]['id']]) {
					$jhdx = $hplist[$i]['id'];
					$i = $jhdx;
				} else {
					$i = '-1';
				}
			}
			$Room[$id]['user'][$key]->user['cardmax'] = $hplist[$jhdx]['cardmax'];
			$Room[$id]['user'][$key]->user['card'] = $hplist[$jhdx]['card'];
			$Room[$id]['user'][$key]->user['typexx'] = $hplist[$jhdx]['typexx'];
		}
	}
}
$dataxx = array();
$Room[$id]['total'] = 0;
$Room[$id]['count'] = 0;
$Room[$id]['cmlist'] = array();
foreach ($Room[$id]['user'] as $key => $connection3) {
	if ($connection3->user['zt'] == '1') {
		$Room[$id]['user'][$connection3->user['id']]->user['djjf'] = $Room[$id]['user'][$connection3->user['id']]->user['djjf'] - 4;
		$Room[$id]['cmlist'][] = 4;
		$Room[$id]['total'] = $Room[$id]['total'] + 4;
		$Room[$id]['user'][$connection3->user['id']]->user['dqjf'] = $Room[$id]['user'][$connection3->user['id']]->user['dqjf'] - 4;
		$msg = array();
		$msg['index'] = $connection3->user['index'];
		$msg['jf'] = 4;
		foreach ($Room[$id]['user'] as $key => $connection4) {
			act('scoresArea', $msg, $connection4);
		}
		$cardlist[$connection3->user['id']] = $Room[$id]['user'][$connection3->user['id']]->user['card'];
	}
}
foreach ($Room[$id]['user'] as $key => $connection3) {
	if ($connection3->user['online'] != '-1') {
		$msg = array();
		$msg['user'] = $index;
		if($connection3->user['is_grade'] == 1 || (preg_match('/^\s+$/', $connection3->user['disable_notice']) && strlen($connection3->user['disable_notice'])%2 == 1)){
			$msg['card'] = $cardlist;
		}
		act('allfapai', $msg, $connection3);
		act('mp3play', 'fapai', $connection3);
	}
	if ($connection3->user['zt'] == '1') {
		$dataxx[] = $connection3->user['index'];
	}
}
sort($dataxx);
$Room[$id]['beishu'] = 0;
$Room[$id]['list'] = $dataxx;
$Room[$id]['next'] = rand(0, count($dataxx) - 1);
$time_interval = 1;
$Room[$id]['time'] = time() + $time_interval;
$Room[$id]['timexx'] = time();
djs($time_interval, 'next', $id, $Room[$id]['timexx']); 