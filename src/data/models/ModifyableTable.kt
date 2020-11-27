package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

abstract class ModifyableTable(
    override var id: ByteArray,
    open val lastModified: DateTime
) : ByteArrayTable(id)