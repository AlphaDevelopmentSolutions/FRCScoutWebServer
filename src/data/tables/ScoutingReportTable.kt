package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ScoutingReport
import org.jetbrains.exposed.sql.ResultRow

object ScoutingReportTable : ModifyTrackedTable<ScoutingReport>("scouting_reports") {
    var createdById = binary("created_by_id", 16)
    var teamAccountId = binary("team_account_id", 16)
    var name = varchar("name", 45)
    var description = varchar("description", 200).nullable()
    var xAxisDataType = varchar("x_axis_data_type", 45).nullable()
    var xAxisDataUnit = varchar("x_axis_data_unit", 45).nullable()
    var chartType = varchar("chart_type", 45).nullable()
    var isPublic = bool("is_public")
    override fun fromResultRow(resultRow: ResultRow): ScoutingReport {
        TODO("Not yet implemented")
    }
}