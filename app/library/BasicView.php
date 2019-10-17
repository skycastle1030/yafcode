<?php
/**
 *     o-o  o-O-o o-o    o-o  o-o    o-o  o  o  o-o  o--o
 *    o       |   |  \  |     |  \  |     |  | |     |
 *    |  -o   |   |   O  o-o  |   O  o-o  O--O  o-o  O-o
 *    o   |   |   |  /      | |  /      | |  |     | |
 *     o-o    o   o-o   o--o  o-o   o--o  o  o o--o  o
 *
 *
 * @Version: 1.0
 * @Author   GTDSDSHSF <n5r9@foxmail.com>
 * @Date:   2017-04-26 20:10:19
 * @Last Modified by:   z
 * @Last Modified time: 2019-04-23 15:00:29
 */
class BasicView extends Yaf_View_Simple
{

    public function actionRender($ob = false)
    {
        $output = $this->actions;
        $sess = Yaf_Session::getInstance();
        //跳过Admin前端权限校验
        if (BasicModel::isCSMManager()) {
            $output = str_replace(["\r\n", "\n", "\r"], '', $output);
            if ($ob == true) {
                return $output;
            }
            echo $output;
            return;
        }
        $pdo = PDO_CsmLocal::getInstance();
        $user = $pdo->get(LOCAL_PREFIX . "admin_user (u)", [
            "[>]" . LOCAL_PREFIX . "admin_basic_group (g)" => ["u.user_basic_group" => "id"],
        ],
            [
                "u.user_id",
                "u.user_basic_group",
                "u.passport",
                "g.permissions",
            ], [
                "u.user_id" => $sess->userid,
            ]);
        $arrPermissions = json_decode($user["permissions"], true);
        $pattern = '/<a href=.*?(\/.*?)(\'|").*?<\/a>/';
        preg_match_all($pattern, $this->actions, $matched);
        $matchedHTML = $matched[0];
        $matchedFullUrl = $matched[1];
        $matchedUrl = [];
        $matchedUrlMap = [];
        foreach ($matchedFullUrl as $idx => $_url) {
            $tmp = explode("?", $_url);
            if ($tmp[0] != "") {
                $u = explode("#", $tmp[0])[0];
                $matchedUrl[] = str_ireplace("\\", "", $u);
                $matchedUrlMapHTML[$u] = $matchedHTML[$idx];
            }
        }
        $_sql = sprintf("
        SELECT node_id,concat('/',node_module,'/',node_controller,'/',node_action)  as node_url from %s where concat('/',node_module,'/',node_controller,'/',node_action) in ('%s')
        ", LOCAL_PREFIX . "admin_auth_nodes", implode("','", $matchedUrl));
        $ownedPermissions = $pdo->query($_sql)->fetchAll();
        $ownedPermissionsUrls = TDAMerge2Arr($ownedPermissions, ["node_url"]);
        foreach ($matchedUrlMapHTML as $_url => $_html) {
            if (!in_array($_url, $ownedPermissionsUrls)) {
                $output = str_replace($_html, '', $output);
            }
        }
        $output = str_replace(["\r\n", "\n", "\r"], '', $output);
        if ($ob == true) {
            return $output;
        }
        echo $output;
    }

    public function getHost()
    {
        return '//' . $_SERVER['HTTP_HOST'];
    }

    public function getLoggedInUserInfo()
    {
        return BasicModel::getLoggedInUserInfo();
    }
}
