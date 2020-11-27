package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ByteArrayTable
import org.jetbrains.exposed.dao.EntityID
import org.jetbrains.exposed.dao.IdTable
import org.jetbrains.exposed.sql.Column
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.Table
import java.util.*

abstract class ByteArrayTable<T: ByteArrayTable>(name: String = "", columnName: String = "id") : Table(name) {
    val id = binary(columnName, 16).primaryKey()

    abstract fun fromResultRow(resultRow: ResultRow): T
}