package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ChecklistItemResult
import org.jetbrains.exposed.sql.*

object ChecklistItemResultTable : ModifyTrackedTable<ChecklistItemResult>("checklist_item_results") {
    var checklistItemId = binary("checklist_item_id", 16)
    var matchId = binary("match_id", 16)
    var status = customEnumeration("status", "ENUM('COMPLETE', 'INCOMPLETE')", { value -> ChecklistItemResult.Companion.Status.valueOf(value as String)}, { it.name }).nullable()
    var completedDate = datetime("completed_date")
    var completedById = binary("completed_by_id", 16)
    var isPublic = bool("is_public")

    override fun fromResultRow(resultRow: ResultRow) =
        ChecklistItemResult(
            resultRow[id],
            resultRow[checklistItemId],
            resultRow[matchId],
            resultRow[status],
            resultRow[completedDate],
            resultRow[completedById],
            resultRow[isPublic]
        )

    override fun insert(obj: ChecklistItemResult) =
        insert {
            it[id] = obj.id
            it[checklistItemId] = obj.checklistItemId
            it[matchId] = obj.matchId
            it[status] = obj.status
            it[completedDate] = obj.completedDate
            it[completedById] = obj.completedById
            it[isPublic] = obj.isPublic
        }

    override fun update(obj: ChecklistItemResult, where: (SqlExpressionBuilder.() -> Op<Boolean>)?): Int =
        update(where ?: { id eq obj.id }) {
            it[checklistItemId] = obj.checklistItemId
            it[matchId] = obj.matchId
            it[status] = obj.status
            it[completedDate] = obj.completedDate
            it[completedById] = obj.completedById
            it[isPublic] = obj.isPublic
        }
}