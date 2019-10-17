<?php

return [
    "plan_status" => [
        1 => "服务中",
        2 => "已完成",
        // 3 => "毁单",
        9 => "已删除",
    ],
    'wzly_service_status' => [
        0 => '服务中',
        10 => '服务已暂停',
        20 => '已撤档',
        30 => '服务已结束',
        40 => '服务已结束,作为被动会员继续服务',
    ],
    //感情状况
    'wzly_customer_relationship_status' => [
        5 => "恋爱中",
        6 => "已结婚",
        21 => "单身",
    ],
    "way_to_service" => [
        10 => "电话",
        20 => "网聊",
        30 => "面谈",
        40 => "视频",
    ],
    "way_to_follow_up" => [
        10 => "电话",
        20 => "网聊",
        30 => "面谈",
        90 => "无",
    ],
    "service_duration" => [
        20 => "60分钟",
        10 => "30分钟",
    ],
    "service_record_status" => [
        0 => "未开启",
        1 => "服务中",
        2 => "已完成",
        9 => "不可用",
    ],
    'timeline_row_type' => [
        10 => '跟进',
        20 => '服务',
        // 30 => '客诉',
    ],
    'album_photo_archives' => [
        10 => '生活照',
        20 => '证件/证书/调解书',
        30 => '服务表格/承诺函',
        40 => '合同/收据',
        30 => '聊天截图',
        50 => '暂停服务',
        60 => '跟进',
        80 => '撤档相关',
        990 => '其他',
    ],
    'attachment_types' => [
        'pdf' => 'Adobe 可移植文档格式 [.pdf]',
        'doc' => 'Microsoft Word 97-2003 [.doc]',
        'docx' => 'Microsoft Word 文件 [.docx]',
        'xls' => 'Microsoft Excel 97-2003 [.xls]',
        'xlsx' => 'Microsoft Excel 文件 [.xlsx]',
    ],
    'wzly' => [
        //排约申请状态
        'application_status' => [
            10 => '待处理',
            20 => '已通过',
            30 => '未通过',
            40 => '已撤销',
            50 => '成功',
            60 => '未成功',
        ],
        //约会阶段
        'date_stage' => [
            1 => '待见面',
            2 => '见面完成',
            3 => '见面取消',
        ],
        'date_stage_display' => [
            0 => '待见面',
            1 => '待见面',
            2 => '见面完成',
            3 => '见面取消',
        ],
        //引用-见面感觉
        'date_feedback' => [
            1 => '很喜欢，愿意交往',
            2 => '满意，想接触看看',
            3 => '一般般',
            4 => '不满意',
        ],
        //排约提醒
        'application_remind_status' => [
            10 => '已提醒',
        ],

        //已过期服务保护
        'expired_service_protect_duration' => [
            '2592000' => '一个月',
            '5184000' => '两个月',
            '7776000' => '三个月',
        ],
    ],
    'quality' => [
        'status' => [
            10 => '待处理',
            20 => '已处理',
            30 => '已解决',
        ],
    ],
    'plan_template_platform_contain' => [
        P_CSM => [
            P_WZLY => '婚恋服务',
            P_AQQW => '情感服务',
            P_MLTS => '形象服务',
        ],
        P_WZLY => [
            P_WZLY => '婚恋服务',
            P_AQQW => '情感服务',
            P_MLTS => '形象服务',
        ],
        P_AQQW => [
            P_AQQW => '情感服务',
            P_MLTS => '形象服务',
        ],
        P_MLTS => [
            P_MLTS => '形象服务',
        ],
    ],
];
