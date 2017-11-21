CREATE TABLE yacrs_lticonsumer(id INTEGER PRIMARY KEY AUTO_INCREMENT, keyHash VARCHAR(40), consumer_key VARCHAR(255), name VARCHAR(80), secret VARCHAR(255));
CREATE TABLE yacrs_session(id INTEGER PRIMARY KEY AUTO_INCREMENT, ownerID VARCHAR(35), title VARCHAR(80), created DATETIME, questions TEXT, currentQuestion INTEGER, questionMode INTEGER, endtime DATETIME, sessionstarttime DATETIME, sessionOpen INTEGER, activeSubsession_id INTEGER, sessionendtime DATETIME, visible INTEGER, allowGuests INTEGER, multiSession INTEGER, ublogRoom INTEGER, maxMessagelength INTEGER, allowQuReview INTEGER, allowTeacherQu INTEGER, courseIdentifier VARCHAR(20), defaultQuActiveSecs INTEGER, extras TEXT);
CREATE TABLE yacrs_extraTeachers(id INTEGER PRIMARY KEY AUTO_INCREMENT, session_id INTEGER, teacherID VARCHAR(35));
CREATE TABLE yacrs_subsession(id INTEGER PRIMARY KEY AUTO_INCREMENT, session_id INTEGER, title VARCHAR(80), starttime DATETIME, endtime DATETIME);
CREATE TABLE yacrs_ltisessionlink(id INTEGER PRIMARY KEY AUTO_INCREMENT, client_id INTEGER, resource_link_id VARCHAR(255), session_id INTEGER);
CREATE TABLE yacrs_userInfo(id INTEGER PRIMARY KEY AUTO_INCREMENT, username VARCHAR(80), name VARCHAR(45), email VARCHAR(85), nickname VARCHAR(45), phone VARCHAR(20), sessionCreator INTEGER, isAdmin INTEGER, teacherPrefs TEXT);
CREATE TABLE yacrs_question(id INTEGER PRIMARY KEY AUTO_INCREMENT, ownerID VARCHAR(35), session_id INTEGER, title VARCHAR(80), definition TEXT, responsetype VARCHAR(20), multiuse INTEGER);
CREATE TABLE yacrs_systemQuestionLookup(id INTEGER PRIMARY KEY AUTO_INCREMENT, qu_id INTEGER, name VARCHAR(10));
CREATE TABLE yacrs_questionInstance(id INTEGER PRIMARY KEY AUTO_INCREMENT, title VARCHAR(80), theQuestion_id INTEGER, inSession_id INTEGER, subsession_id INTEGER, starttime DATETIME, endtime DATETIME, screenshot VARCHAR(60), extras TEXT);
CREATE TABLE yacrs_sessionMember(id INTEGER PRIMARY KEY AUTO_INCREMENT, session_id INTEGER, userID VARCHAR(35), name VARCHAR(45), nickname VARCHAR(45), email VARCHAR(85), user_id INTEGER, joined DATETIME, lastresponse DATETIME, mobile VARCHAR(20));
CREATE TABLE yacrs_response(id INTEGER PRIMARY KEY AUTO_INCREMENT, user_id INTEGER, question_id INTEGER, value TEXT, isPartial INTEGER, time DATETIME);
CREATE TABLE yacrs_message(id INTEGER PRIMARY KEY AUTO_INCREMENT, user_id INTEGER, session_id INTEGER, subsession_id INTEGER, isTeacherQu INTEGER, private INTEGER, posted DATETIME, message TEXT, replyTo_id INTEGER);
CREATE TABLE yacrs_message_tag_link(message_id INTEGER, tag_id INTEGER);
CREATE TABLE yacrs_tag(id INTEGER PRIMARY KEY AUTO_INCREMENT, text VARCHAR(20), session_id INTEGER);

CREATE TABLE `yacrs_apiKey` (
  `id` int(11) NOT NULL,
  `username` varchar(80) NOT NULL,
  `key` varchar(64) NOT NULL,
  `created` bigint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `yacrs_apiKey`
--
ALTER TABLE `yacrs_apiKey`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `yacrs_apiKey`
--
ALTER TABLE `yacrs_apiKey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;COMMIT;
