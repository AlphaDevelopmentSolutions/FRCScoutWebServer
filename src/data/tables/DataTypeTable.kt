package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.DataType
import org.jetbrains.exposed.sql.*

object DataTypeTable : ModifyTrackedTable<DataType>("data_types") {
    var name = varchar("name", 16)
    var canMax = bool("can_max")
    var canMin = bool("can_min")
    var canNullZeros = bool("can_null_zeros")
    var canReport = bool("can_report")

    override fun fromResultRow(resultRow: ResultRow) =
        DataType(
            resultRow[id],
            resultRow[name],
            resultRow[canMax],
            resultRow[canMin],
            resultRow[canNullZeros],
            resultRow[canReport],
            resultRow[deletedDate],
            resultRow[deletedById],
            resultRow[lastModified],
            resultRow[modifiedById]
        )

    override fun insert(obj: DataType) =
        insert {
            it[id] = obj.id
            it[name] = obj.name
            it[canMax] = obj.canMax
            it[canMin] = obj.canMin
            it[canNullZeros] = obj.canNullZeros
            it[canReport] = obj.canReport
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }

    override fun update(obj: DataType, where: (SqlExpressionBuilder.() -> Op<Boolean>)?): Int =
        update(where ?: { id eq obj.id }) {
            it[name] = obj.name
            it[canMax] = obj.canMax
            it[canMin] = obj.canMin
            it[canNullZeros] = obj.canNullZeros
            it[canReport] = obj.canReport
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }
}