package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.RobotInfoKey
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

object RobotInfoKeyTable : ModifyTrackedTable<RobotInfoKey>("robot_info_keys") {
    var stateId = binary("state_id", 16)
    var dataTypeId = binary("data_type_id", 16)
    var name = varchar("name", 100)
    var description = varchar("description", 200).nullable()
    var order = integer("order")
    var min = integer("min").nullable()
    var max = integer("max").nullable()
    var nullZeros = bool("null_zeros").nullable()
    var includeInReports = bool("include_in_reports").nullable()

    override fun fromResultRow(resultRow: ResultRow) =
        RobotInfoKey(
            resultRow[id],
            resultRow[stateId],
            resultRow[dataTypeId],
            resultRow[name],
            resultRow[description],
            resultRow[order],
            resultRow[min],
            resultRow[max],
            resultRow[nullZeros],
            resultRow[includeInReports],
            resultRow[deletedDate],
            resultRow[deletedById],
            resultRow[lastModified],
            resultRow[modifiedById]
        )

    override fun insert(obj: RobotInfoKey) =
        insert {
            it[id] = obj.id
            it[stateId] = obj.stateId
            it[dataTypeId] = obj.dataTypeId
            it[name] = obj.name
            it[description] = obj.description
            it[order] = obj.order
            it[min] = obj.min
            it[max] = obj.max
            it[nullZeros] = obj.nullZeros
            it[includeInReports] = obj.includeInReports
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }

    override fun update(obj: RobotInfoKey) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[stateId] = obj.stateId
            it[dataTypeId] = obj.dataTypeId
            it[name] = obj.name
            it[description] = obj.description
            it[order] = obj.order
            it[min] = obj.min
            it[max] = obj.max
            it[nullZeros] = obj.nullZeros
            it[includeInReports] = obj.includeInReports
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }
}