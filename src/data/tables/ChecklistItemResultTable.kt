package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ChecklistItemResult
import org.jetbrains.exposed.sql.ResultRow

object ChecklistItemResultTable : ModifyTrackedTable<ChecklistItemResult>("checklist_item_results") {
    var checklistItemId = binary("checklist_item_id", 16)
    var matchId = binary("match_id", 16)
    var status = customEnumeration("status", "ENUM('COMPLETE', 'INCOMPLETE')", { value -> ChecklistItemResult.Companion.Status.valueOf(value as String)}, { it.name }).nullable()
    var completedDate = datetime("completed_date")
    var completedById = binary("completed_by_id", 16)
    var isPublic = bool("is_public")
    override fun fromResultRow(resultRow: ResultRow): ChecklistItemResult {
        TODO("Not yet implemented")
    }
}