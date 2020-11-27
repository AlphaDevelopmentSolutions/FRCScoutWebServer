package com.alphadevelopmentsolutions.data.tables

object ScoutCardInfoTable : ModifyTrackedTable("scout_card_info") {
    var matchId = binary("match_id", 16)
    var teamId = binary("team_id", 16)
    var keyId = binary("key_id", 16)
    var value = varchar("value", 200)
    var completedById = binary("completed_by_id", 16)
    var isPublic = bool("is_public")
}