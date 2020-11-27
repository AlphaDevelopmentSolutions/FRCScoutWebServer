package com.alphadevelopmentsolutions.data.tables

object ChecklistItemTable : ModifyTrackedTable("checklist_items") {
    var teamAccountId = binary("team_account_id", 16)
    var yearId = binary("year_id", 16)
    var title = varchar("title", 100)
    var description = varchar("description", 300)
}