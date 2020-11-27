package com.alphadevelopmentsolutions.data.tables

import org.jetbrains.exposed.dao.EntityID
import org.jetbrains.exposed.dao.IdTable
import org.jetbrains.exposed.sql.Column
import org.jetbrains.exposed.sql.Table
import java.util.*

abstract class ModifyableTable<T>(name: String = "") : ByteArrayTable<T>(name) {
    val lastModified = datetime("last_modified")
}