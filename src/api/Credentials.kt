package com.alphadevelopmentsolutions.api

import com.alphadevelopmentsolutions.data.models.AuthToken
import com.alphadevelopmentsolutions.data.models.TeamAccount
import com.alphadevelopmentsolutions.data.models.User
import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import org.joda.time.DateTime

class Credentials(
    val authToken: AuthToken,
    val user: User,
    val teamAccountList: List<TeamAccount>,
    val lastModified: DateTime
)