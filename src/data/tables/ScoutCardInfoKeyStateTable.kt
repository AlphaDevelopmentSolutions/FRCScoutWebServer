package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ScoutCardInfoKeyState
import org.jetbrains.exposed.sql.ResultRow

object ScoutCardInfoKeyStateTable : ModifyTrackedTable<ScoutCardInfoKeyState>("scout_card_info_key_states") {
    var teamAccountId = binary("team_account_id", 16)
    var yearId = binary("year_id", 16)
    var name = varchar("name", 100)
    var description = binary("description", 200).nullable()
    override fun fromResultRow(resultRow: ResultRow): ScoutCardInfoKeyState {
        TODO("Not yet implemented")
    }
}