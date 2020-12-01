package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ChecklistItem
import org.jetbrains.exposed.sql.*

object ChecklistItemTable : ModifyTrackedTable<ChecklistItem>("checklist_items") {
    var teamAccountId = binary("team_account_id", 16)
    var yearId = binary("year_id", 16)
    var title = varchar("title", 100)
    var description = varchar("description", 300)

    override fun fromResultRow(resultRow: ResultRow) =
        ChecklistItem(
            resultRow[id],
            resultRow[teamAccountId],
            resultRow[yearId],
            resultRow[title],
            resultRow[description],
            resultRow[deletedDate],
            resultRow[deletedById],
            resultRow[lastModified],
            resultRow[modifiedById]
        )

    override fun insert(obj: ChecklistItem) =
        insert {
            it[id] = obj.id
            it[teamAccountId] = obj.teamAccountId
            it[yearId] = obj.yearId
            it[title] = obj.title
            it[description] = obj.description
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }

    override fun update(obj: ChecklistItem, where: (SqlExpressionBuilder.() -> Op<Boolean>)?): Int =
        update(where ?: { id eq obj.id }) {
            it[teamAccountId] = obj.teamAccountId
            it[yearId] = obj.yearId
            it[title] = obj.title
            it[description] = obj.description
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }
}