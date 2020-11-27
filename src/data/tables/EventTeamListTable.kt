package com.alphadevelopmentsolutions.data.tables

object EventTeamListTable : ModifyableTable("event_team_list") {
    var teamId = binary("team_account", 16)
    var eventId = binary("event_id", 16)
}