package com.alphadevelopmentsolutions.api

import com.alphadevelopmentsolutions.data.models.*
import com.google.gson.annotations.SerializedName

class AppData(
    @SerializedName("checklist_items") val checklistItemList: MutableList<ChecklistItem> = mutableListOf(),
    @SerializedName("checklist_item_results") val checklistItemResultList: MutableList<ChecklistItemResult> = mutableListOf(),
    @SerializedName("data_types") val dataTypeList: MutableList<DataType> = mutableListOf(),
    @SerializedName("events") val eventList: MutableList<Event> = mutableListOf(),
    @SerializedName("event_team_list") val eventTeamListList: MutableList<EventTeamList> = mutableListOf(),
    @SerializedName("matches") val matchList: MutableList<Match> = mutableListOf(),
    @SerializedName("match_types") val matchTypeList: MutableList<MatchType> = mutableListOf(),
    @SerializedName("robot_info") val robotInfoList: MutableList<RobotInfo> = mutableListOf(),
    @SerializedName("robot_info_keys") val robotInfoKeyList: MutableList<RobotInfoKey> = mutableListOf(),
    @SerializedName("robot_info_key_states") val robotInfoKeyStateList: MutableList<RobotInfoKeyState> = mutableListOf(),
    @SerializedName("robot_media") val robotMediaList: MutableList<RobotMedia> = mutableListOf(),
    @SerializedName("roles") val roleList: MutableList<Role> = mutableListOf(),
    @SerializedName("scout_card_info") val scoutCardInfoList: MutableList<ScoutCardInfo> = mutableListOf(),
    @SerializedName("scout_card_info_keys") val scoutCardInfoKeyList: MutableList<ScoutCardInfoKey> = mutableListOf(),
    @SerializedName("scout_card_info_key_states") val scoutCardInfoKeyStateList: MutableList<ScoutCardInfoKeyState> = mutableListOf(),
    @SerializedName("teams") val teamList: MutableList<Team> = mutableListOf(),
    @SerializedName("team_accounts") val teamAccountList: MutableList<TeamAccount> = mutableListOf(),
    @SerializedName("users") val userList: MutableList<User> = mutableListOf(),
    @SerializedName("user_roles") val userRoleList: MutableList<UserRole> = mutableListOf(),
    @SerializedName("user_team_accounts") val userTeamAccountList: MutableList<UserTeamAccountList> = mutableListOf(),
    @SerializedName("years") val yearList: MutableList<Year> = mutableListOf()
)