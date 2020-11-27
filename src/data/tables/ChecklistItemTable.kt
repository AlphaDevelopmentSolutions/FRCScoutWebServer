package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ChecklistItem
import org.jetbrains.exposed.sql.ResultRow

object ChecklistItemTable : ModifyTrackedTable<ChecklistItem>("checklist_items") {
    var teamAccountId = binary("team_account_id", 16)
    var yearId = binary("year_id", 16)
    var title = varchar("title", 100)
    var description = varchar("description", 300)
    override fun fromResultRow(resultRow: ResultRow): ChecklistItem {
        TODO("Not yet implemented")
    }
}