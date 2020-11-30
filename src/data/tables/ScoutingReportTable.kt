package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ScoutingReport
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

object ScoutingReportTable : ModifyTrackedTable<ScoutingReport>("scouting_reports") {
    var createdById = binary("created_by_id", 16)
    var teamAccountId = binary("team_account_id", 16)
    var name = varchar("name", 45)
    var description = varchar("description", 200).nullable()
    var xAxisDataType = varchar("x_axis_data_type", 45).nullable()
    var xAxisDataUnit = varchar("x_axis_data_unit", 45).nullable()
    var chartType = varchar("chart_type", 45).nullable()
    var isPublic = bool("is_public")

    override fun fromResultRow(resultRow: ResultRow) =
        ScoutingReport(
            resultRow[id],
            resultRow[createdById],
            resultRow[teamAccountId],
            resultRow[name],
            resultRow[description],
            resultRow[xAxisDataType],
            resultRow[xAxisDataUnit],
            resultRow[chartType],
            resultRow[isPublic],
            resultRow[deletedDate],
            resultRow[deletedById],
            resultRow[lastModified],
            resultRow[modifiedById]
        )

    override fun insert(obj: ScoutingReport) =
        insert {
            it[id] = obj.id
            it[createdById] = obj.createdById
            it[teamAccountId] = obj.teamAccountId
            it[name] = obj.name
            it[description] = obj.description
            it[xAxisDataType] = obj.xAxisDataType
            it[xAxisDataUnit] = obj.xAxisDataUnit
            it[chartType] = obj.chartType
            it[isPublic] = obj.isPublic
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }

    override fun update(obj: ScoutingReport) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[createdById] = obj.createdById
            it[teamAccountId] = obj.teamAccountId
            it[name] = obj.name
            it[description] = obj.description
            it[xAxisDataType] = obj.xAxisDataType
            it[xAxisDataUnit] = obj.xAxisDataUnit
            it[chartType] = obj.chartType
            it[isPublic] = obj.isPublic
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }
}