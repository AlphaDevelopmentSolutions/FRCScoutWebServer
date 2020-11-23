package com.alphadevelopmentsolutions.data.tables

import org.jetbrains.exposed.dao.EntityID
import org.jetbrains.exposed.dao.IdTable
import org.jetbrains.exposed.sql.Column
import org.jetbrains.exposed.sql.Table
import java.util.*

open class ByteArrayTable(name: String = "", columnName: String = "id") : Table(name) {
    val id = binary(columnName, 16).primaryKey()
}