create table template_tablefields_common
       (
        id serial not null,
        name varchar not null
       );
comment on table template_tablefields_common is 'Template table used to define common fields for table_list and field_list tables';
comment on column template_tablefields_common.id is 'Unique auto-incremented identifier';
comment on column template_tablefields_common.name is 'Name';
create table table_list 
       (
        constraint pk_table_list primary key (id),
        constraint unq_table_list_name unique (name)
       )
inherits (template_tablefields_common);
comment on table table_list is 'List of darwin 2 database tables';
comment on column table_list.id is 'Unique auto-incremented identifier of a darwin 2 database table';
comment on column table_list.name is 'Table name';
create table field_list
       (
        table_ref integer not null,
        constraint pk_field_list primary key (id),
        constraint unq_field_list_name unique (name, table_ref),
        constraint fk_field_list_table_list foreign key (table_ref) references table_list(id) on delete cascade
       )
inherits (template_tablefields_common);
comment on table field_list is 'List of fields for each darwin 2 db tables';
comment on column field_list.id is 'Unique identifier of a field';
comment on column field_list.name is 'Field name';
comment on column field_list.table_ref is 'Reference to a table definition - id field of table_list table';
create table catalogue_relationships
       (
        table_ref integer not null,
        record_id_1 integer not null,
        record_id_2 integer not null,
        relationship_type relationship_types default 'is child of' not null,
        defined_by_ordered_ids_list integer[],
        constraint unq_catalogue_relationships unique (table_ref, relationship_type, record_id_1, record_id_2),
        constraint fk_catalogue_rel_table_list foreign key (table_ref) references table_list(id)
       );
comment on table catalogue_relationships is 'Stores the relationships between records of a table - synonymy, parenty, current name, original combination, ...';
comment on column catalogue_relationships.table_ref is 'Reference of the table a relationship is defined for - id field of table_list table';
comment on column catalogue_relationships.record_id_1 is 'Identifier of record in relation with an other one (record_id_2)';
comment on column catalogue_relationships.record_id_2 is 'Identifier of record in relation with an other one (record_id_1)';
comment on column catalogue_relationships.relationship_type is 'Type of relation between record 1 and record 2 - synonymy, parenty, current name, original combination, ...';
comment on column catalogue_relationships.defined_by_ordered_ids_list is 'Array of persons identifiers (id fields of people table) having defined this relationship';
create table template_table_record_ref
       (
        table_ref integer not null,
        record_id integer not null
       );
create table catalogue_authors
       (
        author_type catalogues_authors_types default 'main' not null,
        authors_ordered_ids_list integer[] not null,
        defined_by_ordred_ids_list integer[],
        constraint unq_catalogue_authors unique (table_ref, author_type, record_id),
        constraint fk_catalogue_authors_table_list foreign key (table_ref) references table_list(id)
       )
inherits (template_table_record_ref);
comment on table catalogue_authors is 'List of authors of catalogues units - Taxonomy, Chronostratigraphy,...';
comment on column catalogue_authors.table_ref is 'Identifier of table the units come from - id field of table_list table';
comment on column catalogue_authors.record_id is 'Identifier of record concerned in table concerned';
comment on column catalogue_authors.author_type is 'Type of "author" associated to the catalogue unit: Main author, corrector, taking the sense from,...';
comment on column catalogue_authors.authors_ordered_ids_list is 'Array of "authors" identifiers - List of authors associated to the unit concerned - Identifiers are id fields from people table';
comment on column catalogue_authors.defined_by_ordred_ids_list is 'Array of persons having defined this catalogue authors entry - id fields from people table';
create table catalogue_levels
       (
        id serial not null,
        level_type level_types not null,
        level_name varchar not null,
        optional_level boolean default false not null,
        constraint pk_catalogue_levels primary key (id),
        constraint unq_catalogue_levels unique (level_type, level_name)
       );
comment on table catalogue_levels is 'List of hierarchical units levels - organized by type of unit: taxonomy, chroostratigraphy,...';
comment on column catalogue_levels.id is 'Unique identifier of a hierarchical unit level';
comment on column catalogue_levels.level_type is 'Type of unit the levels is applicable to - contained in a predifined list: taxonomy, chronostratigraphy,...';
comment on column catalogue_levels.level_name is 'Name given to level concerned';
comment on column catalogue_levels.optional_level is 'Tells if the level is optional';
create table possible_upper_levels
       (
        level_ref integer not null,
        level_upper_ref integer not null,
        constraint unq_possible_upper_levels unique (level_ref, level_upper_ref),
        constraint fk_possible_upper_levels_catalogue_levels_01 foreign key (level_ref) references catalogue_levels(id) on delete cascade,
        constraint fk_possible_upper_levels_catalogue_levels_02 foreign key (level_upper_ref) references catalogue_levels(id)
       );
comment on table possible_upper_levels is 'For each level, list all the availble parent levels';
comment on column possible_upper_levels.level_ref is 'Reference of current level';
comment on column possible_upper_levels.level_upper_ref is 'Reference of authorized parent level';
create table comments
       (
        table_ref integer not null,
        record_id integer not null,
        notion_concerned varchar not null,
        comment text not null,
        comment_ts tsvector not null,
        comment_language_full_text full_text_language, 
        constraint unq_comments unique (table_ref, record_id, notion_concerned),
        constraint fk_comments_table_list foreign key (table_ref) references table_list(id)
       );
comment on table comments is 'Comments associated to a record of a given table (and maybe a given field) on a given subject';
comment on column comments.table_ref is 'Reference of table a comment is posted for - id field of table_list table';
comment on column comments.record_id is 'Identifier of the record concerned';
comment on column comments.notion_concerned is 'Notion concerned by comment';
comment on column comments.comment is 'Comment';
comment on column comments.comment_ts is 'tsvector form of comment field';
comment on column comments.comment_language_full_text is 'Corresponding language to the language/country reference recognized by full text search to_tsvector function';
create table tags
       (
        id serial not null,
        label varchar not null,
        label_indexed varchar not null,
        constraint pk_tags primary key (id),
        constraint unq_tags_label unique (label_indexed)
       );
comment on table tags is 'List of all tags introduced to describe GTUs';
comment on column tags.id is 'Unique identifier of a tag';
comment on column tags.label is 'Tag';
comment on column tags.label_indexed is 'Indexed form of tag';
create table tag_groups
       (
        id bigserial not null,
        tag_ref integer not null,
        group_name varchar not null,
        group_name_indexed varchar not null,
        color varchar default '#FFFFFF' not null,
        constraint pk_tag_groups primary key (id),
        constraint fk_tag_groups_tags foreign key (tag_ref) references tags(id) on delete cascade,
        constraint unq_tag_groups unique (tag_ref, group_name_indexed)
       );
comment on table tag_groups is 'List of grouped tags';
comment on column tag_groups.id is 'Unique identifier of a grouped tag';
comment on column tag_groups.tag_ref is 'Reference of tag - id field from tags table';
comment on column tag_groups.group_name is 'Group name under which the tag is grouped: Country, River, Mountain,...';
comment on column tag_groups.group_name_indexed is 'Indexed form of a group name';
comment on column tag_groups.color is 'Color associated to the group concerned';
create table gtu
       (
        id serial not null,
        code varchar not null,
        parent_ref integer not null,
        gtu_from_date_seconds date_seconds,
        gtu_from_date_minutes date_minutes,
        gtu_from_date_hours date_hours,
        gtu_from_date_day date_day,
        gtu_from_date_month date_month,
        gtu_from_date_year date_year,
        gtu_from_date timestamp,
        gtu_to_date_seconds date_seconds,
        gtu_to_date_minutes date_minutes,
        gtu_to_date_hours date_hours,
        gtu_to_date_day date_day,
        gtu_to_date_month date_month,
        gtu_to_date_year date_year,
        gtu_to_date timestamp,
        constraint pk_gtu primary key (id),
        constraint fk_gtu_gtu foreign key (parent_ref) references gtu(id) on delete cascade
       );
comment on table gtu is 'Location or sampling units - GeoTemporalUnits';
comment on column gtu.id is 'Unique identifier of a location or sampling unit';
comment on column gtu.code is 'Code given - for sampling units - takes id if none defined';
comment on column gtu.parent_ref is 'Recursive reference to a parent location-sampling unit - id field of gtu table itself';
create table catalogue_properties
       (
        property_type varchar not null,
        property_sub_type varchar,
        property_sub_type_indexed varchar not null,
        date_from timestamp,
        date_from_indexed timestamp not null,
        date_to timestamp,
        date_to_indexed timestamp not null,
        property_unit varchar not null,
        property_min varchar[] not null,
        property_min_unified varchar[] not null,
        property_max varchar[],
        property_max_unified varchar[],
        property_accuracy_unit varchar,
        property_accuracy real[],
        property_accuracy_unified real[],
        property_method varchar,
        property_method_indexed varchar not null,
        property_tool varchar,
        property_tool_indexed varchar not null,
        defined_by_ordered_ids_list integer[],
        constraint unq_catalogue_properties unique (table_ref, record_id, property_type, property_sub_type_indexed, date_from_indexed, date_to_indexed, property_method_indexed, property_tool_indexed),
        constraint fk_catalogue_properties_table_list foreign key (table_ref) references table_list(id)
       )
inherits (template_table_record_ref);
comment on table catalogue_properties is 'All properties or all measurements describing an object in darwin are stored in this table';
comment on column catalogue_properties.table_ref is 'Identifier of the table a property is defined for - id field of table_list table';
comment on column catalogue_properties.record_id is 'Identifier of record a property is defined for';
comment on column catalogue_properties.property_type is 'Type-Category of property - Latitude, Longitude, Ph, Height, Weight, Color, Temperature, Wind direction,...';
comment on column catalogue_properties.property_sub_type is 'Sub type or sub category of property: For Latitudes and Longitudes, precise which type of lat/long it is like Lambert 72, Lambert 92, UTM,...';
comment on column catalogue_properties.property_sub_type_indexed is 'Indexed form of Sub type of property - if subtype is null, takes a generic replacement value';
comment on column catalogue_properties.date_from is 'For a range of measurements, give the measurement start';
comment on column catalogue_properties.date_from_indexed is 'Indexed form of date_from field - if null, takes a generic replacement value';
comment on column catalogue_properties.date_to is 'For a range of measurements, give the measurement stop date/time';
comment on column catalogue_properties.date_to_indexed is 'Indexed form of date_to field - if null, takes a generic replacement value';
comment on column catalogue_properties.property_unit is 'Unit used for property value introduced';
comment on column catalogue_properties.property_min is 'Array of one or more value(s) for the property type and subtype selected - in case of range of values store the minimum value or the mean minimum value - in case of range of all values, stores the whole range';
comment on column catalogue_properties.property_min_unified is 'Unified version of property_min value(s) -> means that the value(s) is/are converted into a common unit allowing comparisons';
comment on column catalogue_properties.property_max is 'Array of one or more value(s) for the property type and subtype selected - in case of range of values store the maximum value or the mean maximum value - in case of range of all values, stores nothing';
comment on column catalogue_properties.property_max_unified is 'Unified version of property_max value(s) -> means that the value(s) is/are converted into a common unit al
lowing comparisons';
comment on column catalogue_properties.property_accuracy_unit is 'Unit used for accuracy value(s)';
comment on column catalogue_properties.property_accuracy is 'Accuracy of property measurement';
comment on column catalogue_properties.property_accuracy_unified is 'Unified version of accuracy on property or sub property value -> means that the value(s) is/are converted into a common unit allowing comparisons';
comment on column catalogue_properties.property_method is 'Method used to collect property value';
comment on column catalogue_properties.property_method_indexed is 'Indexed version of property_method field - if null, takes a generic replacement value';
comment on column catalogue_properties.property_tool is 'Tool used to collect property value';
comment on column catalogue_properties.property_tool_indexed is 'Indexed version of property_tool field - if null, takes a generic replacement value';
comment on column catalogue_properties.defined_by_ordered_ids_list is 'Array of identifiers of persons having defined this property - array of id field from people table';
create table identifications
       (
        notion_concerned notions_concerned not null,
        notion_date timestamp,
        identifiers_ordered_ids_list integer[] not null,
        value_defined varchar,
        value_defined_indexed varchar not null,
        value_defined_ts tsvector,
        determination_status varchar,
        defined_by_ordered_ids_list integer[],
        order_by integer default 1 not null,
        constraint unq_identifications unique (table_ref, record_id, notion_concerned, value_defined_indexed),
        constraint fk_identifications_table_list foreign key (table_ref) references table_list(id)
       )
inherits (template_table_record_ref);
comment on table identifications is 'History of identifications';
comment on column identifications.table_ref is 'Reference of table an identification is introduced for';
comment on column identifications.record_id is 'Id of record concerned by an identification entry';
comment on column identifications.notion_concerned is 'Type of entry: Identification on a specific concern';
comment on column identifications.notion_date is 'Date of identification or preparation';
comment on column identifications.identifiers_ordered_ids_list is 'Array of who made the identifications - array of id field from people table';
comment on column identifications.value_defined is 'When making identification, stores the value resulting of this identification';
comment on column identifications.value_defined_ts is 'tsvector form of value_defined field';
comment on column identifications.value_defined_indexed is 'Indexed form of value_defined field';
comment on column identifications.determination_status is 'Status of identification - can either be a percentage of certainty or a code describing the identification step in the process';
comment on column identifications.defined_by_ordered_ids_list is 'Array of persons who have defined this entry - array of id fields from people table';
create table expertises
       (
        expert_ref integer not null,
        defined_by_ordered_ids_list integer[],
        order_by integer default 1 not null,
        constraint unq_expertises unique (table_ref, record_id, expert_ref),
        constraint fk_expertises_table_list foreign key (table_ref) references table_list(id)
       )
inherits (template_table_record_ref);
comment on table expertises is 'History of expertises';
comment on column expertises.table_ref is 'Reference of table an expertise is introduced for';
comment on column expertises.record_id is 'Id of record concerned by an expertise entry';
comment on column expertises.defined_by_ordered_ids_list is 'Array of persons who have defined this entry - array of id fields from people table';
create table class_vernacular_names
       (
        id serial not null,
        community varchar not null,
        defined_by_ordered_ids_list integer[],
        constraint pk_class_vernacular_names primary key (id),
        constraint unq_class_vernacular_names unique (table_ref, record_id, community),
        constraint fk_class_vernacular_names_table_list foreign key (table_ref) references table_list(id)
       )
inherits (template_table_record_ref);
comment on table class_vernacular_names is 'Contains the language communities a unit name translation is available for';
comment on column class_vernacular_names.id is 'Unique identifier of a language community vernacular name';
comment on column class_vernacular_names.table_ref is 'Reference of the unit table a vernacular name for a language community has to be defined - id field of table_list table';
comment on column class_vernacular_names.record_id is 'Identifier of record a vernacular name for a language community has to be defined';
comment on column class_vernacular_names.community is 'Language community, a unit translation is available for';
comment on column class_vernacular_names.defined_by_ordered_ids_list is 'Array of persons ids having defined this entry';
create table vernacular_names
       (
        vernacular_class_ref integer not null,
        name varchar not null,
        name_ts tsvector not null,
        name_indexed varchar not null,
        language_country_full_text full_text_language,
        constraint unq_vernacular_names unique (vernacular_class_ref, name_indexed),
        constraint fk_vernacular_class_class_vernacular_names foreign key (vernacular_class_ref) references class_vernacular_names(id) on delete cascade
       );
comment on table vernacular_names is 'List of vernacular names for a given unit and a given language community';
comment on column vernacular_names.vernacular_class_ref is 'Identifier of a unit/language community entry - id field of class_vernacular_names table';
comment on column vernacular_names.name is 'Vernacular name';
comment on column vernacular_names.name_ts is 'tsvector version of name field';
comment on column vernacular_names.name_indexed is 'Indexed form of vernacular name';
comment on column vernacular_names.language_country_full_text is 'Language used by full text search to_tsvector function';
create table expeditions
       (
        id serial not null,
        name varchar not null,
	name_ts tsvector not null,
        name_indexed varchar not null,
        name_language_full_text full_text_language,
        expedition_from_date_day date_day,
        expedition_from_date_month date_month,
        expedition_from_date_year date_year,
        expedition_from_date date,
        expedition_to_date_day date_day,
        expedition_to_date_month date_month,
        expedition_to_date_year date_year,
        expedition_to_date date,
        constraint pk_expeditions primary key (id)
       );
comment on table expeditions is 'List of expeditions made to collect specimens';
comment on column expeditions.id is 'Unique identifier of an expedition';
comment on column expeditions.name is 'Expedition name';
comment on column expeditions.name_ts is 'tsvector version of name field';
comment on column expeditions.name_indexed is 'Indexed form of expedition name';
comment on column expeditions.name_language_full_text is 'Language associated to language/country reference used by full text search to_tsvector function';
comment on column expeditions.expedition_from_date_day is 'Start day';
comment on column expeditions.expedition_from_date_month is 'Start month';
comment on column expeditions.expedition_from_date_year is 'Start year';
comment on column expeditions.expedition_to_date_day is 'End day';
comment on column expeditions.expedition_to_date_month is 'End month';
comment on column expeditions.expedition_to_date_year is 'End year';
comment on column expeditions.expedition_from_date is 'When all three from date fields are filled, this field contains the full date composition - will help for dates comparisons';
comment on column expeditions.expedition_to_date is 'When all three to date fields are filled, this field contains the full date composition - will help for dates comparisons';
create table template_people
       (
        id serial not null,
        is_physical boolean default true not null,
        sub_type varchar,
        public_class public_classes default 'public' not null,
        formated_name varchar not null,
        formated_name_indexed varchar not null,
        formated_name_ts tsvector not null,
        family_name varchar not null,
        given_name varchar,
        additional_names varchar,
        birth_date_day date_day,
        birth_date_day_indexed smallint default 0 not null,
        birth_date_month date_month,
        birth_date_month_indexed smallint default 0 not null,
        birth_date_year date_year,
        birth_date_year_indexed smallint default 0 not null,
        birth_date date,
        gender genders,
        sort_string varchar(36) not null
       );
comment on table template_people is 'Template table used to describe user/people tables';
comment on column template_people.id is 'Unique identifier of a user/person';
comment on column template_people.is_physical is 'Type of user/person: physical or moral - true is physical, false is moral';
comment on column template_people.sub_type is 'Used for moral user/persons: precise nature - public institution, asbl, sprl, sa,...';
comment on column template_people.public_class is 'Tells public nature of user/person information - public is default value';
comment on column template_people.formated_name is 'Complete user/person formated name (with honorific mention, prefixes, suffixes,...) - By default composed with family_name and given_name fields, but can be modified by hand';
comment on column template_people.formated_name_ts is 'tsvector form of formated_name field';
comment on column template_people.formated_name_indexed is 'Indexed form of formated_name field';
comment on column template_people.family_name is 'Family name for physical user/persons and Organisation name for moral user/persons';
comment on column template_people.given_name is 'User/person''s given name - usually first name';
comment on column template_people.additional_names is 'Any additional names given to user/person';
comment on column template_people.birth_date_day is 'Day of birth/creation';
comment on column template_people.birth_date_day_indexed is 'Indexed form of birth_date_day field';
comment on column template_people.birth_date_month is 'Month of birth/creation';
comment on column template_people.birth_date_month_indexed is 'Indexed form of birth_date_month field';
comment on column template_people.birth_date_year is 'Year of birth/creation';
comment on column template_people.birth_date_year_indexed is 'Indexed form of birth_date_year field';
comment on column template_people.birth_date is 'Birth/Creation date composed from the three birth/creation date fields: birth_date_day, birth_date_month, birth_date_year';
comment on column template_people.gender is 'For physical user/persons give the gender: M or F';
comment on column template_people.sort_string is 'String used for sorting - composed from family_name_indexed and given_name_indexed fields';
create table template_people_languages
       (
        language_country varchar default 'eng_GB' not null,
        mother boolean default true not null,
        prefered_language boolean default false not null
       );
comment on table template_people_languages is 'Template supporting users/people languages table definition';
comment on column template_people_languages.language_country is 'Reference of Language - language_country field of languages_countries table';
comment on column template_people_languages.mother is 'Flag telling if its mother language or not';
create table people
       (
        db_people_type integer default 1 not null,
        end_date_day date_day,
        end_date_day_indexed smallint default 0 not null,
        end_date_month date_month,
        end_date_month_indexed smallint default 0 not null,
        end_date_year date_year,
        end_date_year_indexed smallint default 0 not null,
        end_date date,
        constraint pk_people primary key (id),
        constraint unq_people unique (is_physical, formated_name_indexed, birth_date_day_indexed, birth_date_month_indexed, birth_date_year_indexed, end_date_day_indexed, end_date_month_indexed, end_date_year_indexed)
       )
inherits (template_people);
comment on table people is 'All physical and moral persons used in the application are here stored';
comment on column people.id is 'Unique identifier of a person';
comment on column people.is_physical is 'Type of person: physical or moral - true is physical, false is moral';
comment on column people.sub_type is 'Used for moral persons: precise nature - public institution, asbl, sprl, sa,...';
comment on column people.public_class is 'Tells public nature of person information - public is default value';
comment on column people.formated_name is 'Complete person formated name (with honorific mention, prefixes, suffixes,...) - By default composed with family_name and given_name fields, but can be modified by hand';
comment on column people.formated_name_ts is 'tsvector form of formated_name field';
comment on column people.formated_name_indexed is 'Indexed form of formated_name field';
comment on column people.family_name is 'Family name for physical persons and Organisation name for moral persons';
comment on column people.given_name is 'User/person''s given name - usually first name';
comment on column people.additional_names is 'Any additional names given to person';
comment on column people.birth_date_day is 'Day of birth/creation';
comment on column people.birth_date_day_indexed is 'Indexed form of birth_date_day field';
comment on column people.birth_date_month is 'Month of birth/creation';
comment on column people.birth_date_month_indexed is 'Indexed form of birth_date_month field';
comment on column people.birth_date_year is 'Year of birth/creation';
comment on column people.birth_date_year_indexed is 'Indexed form of birth_date_year field';
comment on column people.birth_date is 'Birth/Creation date composed from the three birth/creation date fields: birth_date_day, birth_date_month, birth_date_year';
comment on column people.gender is 'For physical persons give the gender: M or F';
comment on column people.sort_string is 'String used for sorting - composed from family_name_indexed and given_name_indexed fields';
comment on column people.db_people_type is 'Sum of numbers in an arithmetic suite (1,2,4,8,...) that gives a unique number identifying people roles - each roles represented by one of the number in the arithmetic suite: 1 is contact, 2 is author, 4 is identifier, 8 is expert, 16 is collector,...';
comment on column people.end_date_day is 'End date day';
comment on column people.end_date_day_indexed is 'Indexed form of end date day';
comment on column people.end_date_month is 'End date month';
comment on column people.end_date_month_indexed is 'Indexed form of end date month';
comment on column people.end_date_year is 'End date year';
comment on column people.end_date_year_indexed is 'Indexed form of end date year';
comment on column people.end_date is 'End date composed from the three end date fields: end_date_day, end_date_month, end_date_year';
create table users
       (
        constraint pk_users primary key (id),
        constraint unq_users unique (is_physical, formated_name_indexed, birth_date_day_indexed, birth_date_month_indexed, birth_date_year_indexed)
       )
inherits (template_people);
comment on table users is 'List all application users';
comment on column users.id is 'Unique identifier of a user';
comment on column users.is_physical is 'Type of user: physical or moral - true is physical, false is moral';
comment on column users.sub_type is 'Used for moral users: precise nature - public institution, asbl, sprl, sa,...';
comment on column users.public_class is 'Tells public nature of user information - public is default value';
comment on column users.formated_name is 'Complete user formated name (with honorific mention, prefixes, suffixes,...) - By default composed with family_name and given_name fields, but can be modified by hand';
comment on column users.formated_name_ts is 'tsvector form of formated_name field';
comment on column users.formated_name_indexed is 'Indexed form of formated_name field';
comment on column users.family_name is 'Family name for physical users and Organisation name for moral users';
comment on column users.given_name is 'User/user''s given name - usually first name';
comment on column users.additional_names is 'Any additional names given to user';
comment on column users.birth_date_day is 'Day of birth/creation';
comment on column users.birth_date_day_indexed is 'Indexed form of birth_date_day field';
comment on column users.birth_date_month is 'Month of birth/creation';
comment on column users.birth_date_month_indexed is 'Indexed form of birth_date_month field';
comment on column users.birth_date_year is 'Year of birth/creation';
comment on column users.birth_date_year_indexed is 'Indexed form of birth_date_year field';
comment on column users.birth_date is 'Birth/Creation date composed from the three birth/creation date fields: birth_date_day, birth_date_month, birth_date_year';
comment on column users.gender is 'For physical users give the gender: M or F';
comment on column users.sort_string is 'String used for sorting - composed from family_name_indexed and given_name_indexed fields';
create table people_languages
       (
        people_ref integer not null,
        constraint unq_people_languages unique (people_ref, language_country),
        constraint fk_people_languages_people foreign key (people_ref) references people(id) on delete cascade
       )
inherits (template_people_languages);
comment on table people_languages is 'Languages spoken by a given person';
comment on column people_languages.people_ref is 'Reference of person - id field of people table';
comment on column people_languages.language_country is 'Reference of Language - language_country field of languages_countries table';
comment on column people_languages.mother is 'Flag telling if its mother language or not';
create table users_languages
       (
        user_ref integer not null,
        constraint unq_users_languages unique (user_ref, language_country),
        constraint fk_users_languages_people foreign key (user_ref) references users(id) on delete cascade
       )
inherits (template_people_languages);
comment on table users_languages is 'Languages spoken by a given user';
comment on column users_languages.user_ref is 'Reference of user - id field of users table';
comment on column users_languages.language_country is 'Reference of Language - language_country field of languages_countries table';
comment on column users_languages.mother is 'Flag telling if its mother language or not';
create table multimedia
       (
        id serial not null,
        is_digital boolean default true not null,
        type multimedia_types default 'image' not null,
        title varchar not null,
        title_indexed varchar not null,
        subject varchar default '/' not null,
        coverage coverages default 'temporal' not null,
        apercu_path varchar,
        copyright varchar,
        license varchar,
        uri varchar,
        descriptive_ts tsvector not null,
        descriptive_full_text_language full_text_language,
        creation_date date,
        publication_date date,
        constraint pk_multimedia primary key (id),
        constraint unq_multimedia unique (is_digital, type, title_indexed)
       );
comment on table multimedia is 'Stores all multimedia objects encoded in DaRWIN 2.0';
comment on column multimedia.id is 'Unique identifier of a multimedia object';
comment on column multimedia.is_digital is 'Flag telling if the object is digital (true) or physical (false)';
comment on column multimedia.type is 'Main multimedia object type: image, sound, video,...';
comment on column multimedia.title is 'Object title';
comment on column multimedia.title_indexed is 'Indexed form of title field';
comment on column multimedia.subject is 'Multimedia object subject (as required by Dublin Core...)';
comment on column multimedia.coverage is 'Coverage of multimedia object: spatial or temporal (as required by Dublin Core...)';
comment on column multimedia.apercu_path is 'URI path to the thumbnail illustrating the object';
comment on column multimedia.copyright is 'Copyright notice';
comment on column multimedia.license is 'License notice';
comment on column multimedia.uri is 'URI of object if digital';
comment on column multimedia.creation_date is 'Object creation date';
comment on column multimedia.publication_date is 'Object publication date';
create table template_people_users_comm_common
       (
        id serial not null,
        person_user_ref integer not null,
        comm_type comm_types default 'address' not null,
        entry varchar not null
       );
comment on table template_people_users_comm_common is 'Template table used to construct people communication tables (tel and e-mail)';
comment on column template_people_users_comm_common.id is 'Unique identifier of a person/user communication entry';
comment on column template_people_users_comm_common.person_user_ref is 'Reference of person/user - id field of people/users table';
comment on column template_people_users_comm_common.comm_type is 'Type of communication table concerned: address, phone or e-mail';
comment on column template_people_users_comm_common.entry is 'Communication entry';
create table template_people_users_rel_common
       (
        organization_unit varchar,
        person_user_role varchar,
        activity_period varchar
       );
comment on table template_people_users_rel_common is 'Template table used to propagate three field in different tables depending it''s people or user dedicated';
comment on column template_people_users_rel_common.organization_unit is 'When a physical person/user is in relationship with a moral one, indicates the department or unit the person/user is related to';
comment on column template_people_users_rel_common.person_user_role is 'Role the person/user have in the moral person he depends of';
comment on column template_people_users_rel_common.activity_period is 'Person/User activity period';
create table template_people_users_addr_common
       (
        po_box varchar,
        extended_address varchar,
        locality varchar not null,
        region varchar,
        zip_code varchar,
        country varchar not null
       );
comment on table template_people_users_addr_common is 'Template table used to construct addresses tables for people/users';
comment on column template_people_users_addr_common.po_box is 'PO Box';
comment on column template_people_users_addr_common.extended_address is 'Address extension: State, Special post zip code characters,...';
comment on column template_people_users_addr_common.locality is 'Locality';
comment on column template_people_users_addr_common.region is 'Region';
comment on column template_people_users_addr_common.zip_code is 'zip code';
comment on column template_people_users_addr_common.country is 'Country';
create table people_relationships
       (
        relationship_type people_relationship_types default 'belongs to' not null,
        person_1_ref integer not null,
        person_2_ref integer not null,
        person_title varchar,
        path varchar,
        constraint unq_people_relationships unique (relationship_type, person_1_ref, person_2_ref),
        constraint fk_people_relationships_people_01 foreign key (person_1_ref) references people(id) on delete cascade,
        constraint fk_people_relationships_people_02 foreign key (person_2_ref) references people(id)
       )
inherits (template_people_users_rel_common);
comment on table people_relationships is 'Relationships between people - mainly between physical person and moral person: relationship of dependancy';
comment on column people_relationships.relationship_type is 'Type of relationship between two persons: belongs to, is department of, is section of, works for,...';
comment on column people_relationships.person_1_ref is 'Reference of person to be puted in relationship with an other - id field of people table';
comment on column people_relationships.person_2_ref is 'Reference of person puted the person puted in relationship with is dependant of - id field of people table';
comment on column people_relationships.organization_unit is 'When a physical person is in relationship with a moral one, indicates the department or unit the person is related to';
comment on column people_relationships.person_title is 'Person title';
comment on column people_relationships.person_user_role is 'Person role in the organization referenced';
comment on column people_relationships.activity_period is 'Main person activity period or person activity period in the organization referenced';
comment on column people_relationships.path is 'Hierarchical path of the organization structure';
create table people_comm
       (
        tag varchar[] not null,
        constraint pk_people_comm primary key (id),
        constraint unq_people_comm unique (comm_type, person_user_ref, entry),
        constraint fk_people_comm_people foreign key (person_user_ref) references people(id) on delete cascade
       )
inherits (template_people_users_comm_common);
comment on table people_comm is 'People phones and e-mails';
comment on column people_comm.id is 'Unique identifier of a person communication mean entry';
comment on column people_comm.person_user_ref is 'Reference of person - id field of people table';
comment on column people_comm.comm_type is 'Type of communication table concerned: phone or e-mail';
comment on column people_comm.entry is 'Communication entry';
comment on column people_comm.comm_type is 'Type of communication table concerned: address, phone or e-mail';
comment on column people_comm.tag is 'Array of descriptive tags: internet, tel, fax, pager, public, private,...';
create table people_addresses
       (
        tag varchar[] not null,
        address_parts_ts tsvector not null,
        constraint pk_people_addresses primary key (id),
        constraint unq_people_addresses unique (person_user_ref, entry, locality, country),
        constraint fk_people_addresses_people foreign key (person_user_ref) references people(id) on delete cascade
       )
inherits (template_people_users_comm_common, template_people_users_addr_common);
comment on table people_addresses is 'People addresses';
comment on column people_addresses.address_parts_ts is 'tsvector column used to search an address part';
comment on column people_addresses.id is 'Unique identifier of a person address';
comment on column people_addresses.person_user_ref is 'Reference of the person concerned - id field of people table';
comment on column people_addresses.po_box is 'PO Box';
comment on column people_addresses.extended_address is 'Address extension: State, zip code suffix,...';
comment on column people_addresses.entry is 'Street address';
comment on column people_addresses.locality is 'Locality';
comment on column people_addresses.country is 'Country';
comment on column people_addresses.region is 'Region';
comment on column people_addresses.zip_code is 'Zip code';
comment on column people_addresses.comm_type is 'Type of communication table concerned: address, phone or e-mail';
comment on column people_addresses.tag is 'Array of descriptive tags: home, work,...';
create table users_comm
       (
        tag varchar[] not null,
        constraint pk_users_comm primary key (id),
        constraint unq_users_comm unique (comm_type, person_user_ref, entry),
        constraint fk_users_comm_users foreign key (person_user_ref) references users(id) on delete cascade
       )
inherits (template_people_users_comm_common);
comment on table users_comm is 'Users phones and e-mails';
comment on column users_comm.id is 'Unique identifier of a users communication mean entry';
comment on column users_comm.person_user_ref is 'Reference of user - id field of user table';
comment on column users_comm.comm_type is 'Type of communication table concerned: phone or e-mail';
comment on column users_comm.entry is 'Communication entry';
comment on column users_comm.comm_type is 'Type of communication table concerned: address, phone or e-mail';
comment on column users_comm.tag is 'Array of descriptive tags: internet, tel, fax, pager, public, private,...';
create table users_addresses
       (
        tag varchar[] not null,
        address_parts_ts tsvector not null,
        constraint pk_users_addresses primary key (id),
        constraint unq_users_addresses unique (person_user_ref, entry, locality, country),
        constraint fk_users_addresses_users foreign key (person_user_ref) references users(id) on delete cascade
       )
inherits (template_people_users_comm_common, template_people_users_rel_common, template_people_users_addr_common);
comment on table users_addresses is 'Users addresses';
comment on column users_addresses.address_parts_ts is 'tsvector column used to search an address part';
comment on column users_addresses.id is 'Unique identifier of a user address';
comment on column users_addresses.person_user_ref is 'Reference of the user concerned - id field of users table';
comment on column users_addresses.po_box is 'PO Box';
comment on column users_addresses.extended_address is 'Address extension: State, zip code suffix,...';
comment on column users_addresses.entry is 'Street address';
comment on column users_addresses.locality is 'Locality';
comment on column users_addresses.country is 'Country';
comment on column users_addresses.region is 'Region';
comment on column users_addresses.zip_code is 'Zip code';
comment on column users_addresses.organization_unit is 'When a physical user is in relationship with a moral one, indicates the department or unit the user is related to';
comment on column users_addresses.person_user_role is 'User role in the organization referenced';
comment on column users_addresses.activity_period is 'Main user activity period or user activity period in the organization referenced';
comment on column users_addresses.comm_type is 'Type of communication table concerned: address, phone or e-mail';
comment on column users_addresses.tag is 'Array of descriptive tags: home, work,...';
create table users_login_infos
       (
        user_ref integer not null,
        login_type login_types default 'local' not null,
        user_name varchar,
        password varchar,
        system_id varchar,
        db_user_type smallint default 1 not null,
        constraint unq_users_login_infos unique (user_ref, login_type),
        constraint fk_users_login_infos_users foreign key (user_ref) references users(id) on delete cascade
       );
comment on table users_login_infos is 'Contains the login/password informations of DaRWIN 2 users';
comment on column users_login_infos.user_ref is 'Identifier of user - id field of users table';
comment on column users_login_infos.login_type is 'Type of identification system';
comment on column users_login_infos.user_name is 'For some system (local, ldap, kerberos,...) provides the username (encrypted form)';
comment on column users_login_infos.password is 'For some system (local, ldap, kerberos,...) provides the password (encrypted form)';
comment on column users_login_infos.system_id is 'For some system (shibbolet, openID,...) provides the user id';
comment on column users_login_infos.db_user_type is 'Integer is representing a role: 1 for registered user, 2 for encoder, 4 for collection manager, 8 for system admin,...';
create table template_people_users_multimedia
       (
        person_user_ref integer not null,
        object_ref integer not null,
        category people_multimedia_categories default 'avatar' not null,
        constraint fk_template_people_users_multimedia foreign key (object_ref) references multimedia(id) on delete cascade
       );
comment on table template_people_users_multimedia is 'Template table used to construct people/users associated multimedia table';
comment on column template_people_users_multimedia.person_user_ref is 'Reference of person/user - id field of people/users table';
comment on column template_people_users_multimedia.object_ref is 'Reference of multimedia object - id field of multimedia table';
comment on column template_people_users_multimedia.category is 'Category qualifying the multimedia object use for this person';
create table people_multimedia
       (
        constraint unq_people_multimedia unique (person_user_ref, object_ref, category),
        constraint fk_people_multimedia_people foreign key (person_user_ref) references people(id) on delete cascade
       )
inherits (template_people_users_multimedia);
comment on table people_multimedia is 'Multimedia objects linked to persons';
comment on column people_multimedia.person_user_ref is 'Reference of person concerned - id field of people table';
comment on column people_multimedia.object_ref is 'Reference of multimedia object associated - id field of multimedia table';
comment on column people_multimedia.category is 'Object catégory: avatar, spelled name,...';
create table users_multimedia
       (
        constraint unq_users_multimedia unique (person_user_ref, object_ref, category),
        constraint fk_users_multimedia_users foreign key (person_user_ref) references users(id) on delete cascade
       )
inherits (template_people_users_multimedia);
comment on table users_multimedia is 'Multimedia objects linked to users';
comment on column users_multimedia.person_user_ref is 'Reference of user concerned - id field of users table';
comment on column users_multimedia.object_ref is 'Reference of multimedia object associated - id field of multimedia table';
comment on column users_multimedia.category is 'Object catégory: avatar, spelled name,...';
create table collections
       (
        id serial not null,
        code varchar not null,
        name varchar not null,
        institution_ref integer not null,
        main_manager_ref integer not null,
        parent_collection_ref integer,
        path varchar not null,
        constraint pk_collections primary key (id),
        constraint fk_collections_institutions foreign key (institution_ref) references people(id),
        constraint fk_collections_collections foreign key (parent_collection_ref) references collections(id) on delete cascade,
        constraint fk_collections_users foreign key (main_manager_ref) references users(id),
        constraint unq_collections unique (institution_ref, path)
       );
comment on table collections is 'List of all collections encoded in DaRWIN 2';
comment on column collections.id is 'Unique identifier of a collection';
comment on column collections.code is 'Code given to collection';
comment on column collections.name is 'Collection name';
comment on column collections.institution_ref is 'Reference of institution current collection belongs to - id field of people table';
comment on column collections.parent_collection_ref is 'Recursive reference to collection table itself to represent collection parenty/hierarchy';
comment on column collections.path is 'Descriptive path for collection hierarchy, each level separated by a /';
create table template_collections_users
       (
        collection_ref integer default 0 not null,
        user_ref integer default 0 not null
       );
comment on table template_collections_users is 'Template table used to construct collections rights tables';
comment on column template_collections_users.collection_ref is 'Reference of collection concerned - id field of collections table';
comment on column template_collections_users.user_ref is 'Reference of user - id field of users table';
create table collections_admin
       (
        constraint unq_collections_admin unique (collection_ref, user_ref),
        constraint fk_collections_admin_collections foreign key (collection_ref) references collections(id) on delete cascade,
        constraint fk_collections_admin_users foreign key (user_ref) references users(id) on delete cascade
       )
inherits (template_collections_users);
comment on table collections_admin is 'Stores the list of collections administrators';
comment on column collections_admin.collection_ref is 'Reference of collection concerned - id field of collections table';
comment on column collections_admin.user_ref is 'Reference of user - id field of users table';
create table collections_rights
       (
        rights smallint default 1 not null,
        constraint fk_collections_rights_users foreign key (user_ref) references users(id) on delete cascade,
        constraint fk_collections_rights_collections foreign key (collection_ref) references collections(id) on delete cascade,
        constraint unq_collections_rights unique (collection_ref, user_ref)
       )
inherits (template_collections_users);
comment on table collections_rights is 'List of rights of given users on given collections';
comment on column collections_rights.rights is 'Integer value resulting of the sum of integers from an arithmetic suite. Each number of this suite represent a right on the collection: 1 for read, 2 for insert, 4 for update and 8 for delete - A rights value of 11 means read-insert-delete (1+2+8)';
comment on column collections_rights.collection_ref is 'Reference of collection concerned - id field of collections table';
comment on column collections_rights.user_ref is 'Reference of user - id field of users table';
create table collections_fields_visibilities
       (
        field_group_name varchar not null,
        db_user_type smallint default 1 not null,
        searchable boolean default true not null,
        visible boolean default true not null,
        constraint unq_collections_fields_visibilities unique (collection_ref, user_ref, field_group_name, db_user_type),
        constraint fk_collections_fields_visibilities_collections foreign key (collection_ref) references collections(id) on delete cascade,
        constraint fk_collections_fields_visibilities_users foreign key (user_ref) references users(id) on delete cascade
       )
inherits (template_collections_users);
comment on table collections_fields_visibilities is 'This table tells which group of fields can be searchable and/or visible by a user role or a given precise user - for specimens tables, give the possibility to manage these field visibilities per collections';
comment on column collections_fields_visibilities.collection_ref is 'Reference of collection concerned - id field of collections table';
comment on column collections_fields_visibilities.user_ref is 'Reference of user - id field of users table';
comment on column collections_fields_visibilities.field_group_name is 'Group of fields name';
comment on column collections_fields_visibilities.db_user_type is 'Integer is representing a role: 1 for registered user, 2 for encoder, 4 for collection manager, 8 for system admin,...';
comment on column collections_fields_visibilities.searchable is 'Flag telling if the field group is searchable - meaning these fields will appear as search criterias in the search form';
comment on column collections_fields_visibilities.visible is 'Flag telling if the field group is visible - meaning these fields will be displayable in the result table';
create table users_coll_rights_asked
       (
        field_group_name varchar not null,
        db_user_type smallint not null,
        searchable boolean default true not null,
        visible boolean default true not null,
        motivation varchar not null,
        asking_date_time update_date_time,
        with_sub_collections boolean default true not null,
        constraint unq_users_coll_rights_asked unique (collection_ref, user_ref, field_group_name, db_user_type),
        constraint fk_users_coll_rights_asked_collections foreign key (collection_ref) references collections(id) on delete cascade,
        constraint fk_users_coll_rights_asked_users foreign key (user_ref) references users(id) on delete cascade
       )
inherits (template_collections_users);
comment on table users_coll_rights_asked is 'List all rights asked by a registered user or encoder to collection managers';
comment on column users_coll_rights_asked.collection_ref is 'Reference of collection concerned - id field of collections table';
comment on column users_coll_rights_asked.user_ref is 'Reference of user - id field of users table';
comment on column users_coll_rights_asked.field_group_name is 'Group of fields name';
comment on column users_coll_rights_asked.db_user_type is 'Integer is representing a role: 1 for registered user, 2 for encoder, 4 for collection manager, 8 for system admin,...';
comment on column users_coll_rights_asked.searchable is 'Flag telling if the field group is searchable - meaning these fields will appear as search criterias in the search form';
comment on column users_coll_rights_asked.visible is 'Flag telling if the field group is visible - meaning these fields will be displayable in the result table';
comment on column users_coll_rights_asked.motivation is 'Motivation given by asker';
comment on column users_coll_rights_asked.asking_date_time is 'Telling when right ask was done';
comment on column users_coll_rights_asked.with_sub_collections is 'Rights are asked on a single collection or on this collection with all the sub-collections included ?';
create table record_visibilities
       (
        db_user_type smallint default 1 not null,
        user_ref integer default 0 not null,
        visible boolean default true not null,
        constraint unq_record_visibilities unique (table_ref, record_id, user_ref, db_user_type),
        constraint fk_record_visibilities_table_list foreign key (table_ref) references table_list(id),
        constraint fk_record_visibilities_users foreign key (user_ref) references users(id) on delete cascade
       )
inherits (template_table_record_ref);
comment on table record_visibilities is 'Manage visibility of records for all DaRWIN 2 tables - visibility per user type and/or specific user';
comment on column record_visibilities.user_ref is 'Reference of user - id field of users table';
comment on column record_visibilities.db_user_type is 'Integer is representing a role: 1 for registered user, 2 for encoder, 4 for collection manager, 8 for system admin,...';
comment on column record_visibilities.table_ref is 'Reference of table concerned - id field of table_list table';
comment on column record_visibilities.record_id is 'ID of record a visibility is defined for';
comment on column record_visibilities.visible is 'Flag telling if record is visible or not';
create table users_workflow
       (
        user_ref integer not null,
        status workflow_status default 'to check' not null,
        modification_date_time update_date_time,
        comment varchar,
        constraint fk_users_workflow_table_list foreign key (table_ref) references table_list(id),
        constraint fk_users_workflow_users foreign key (user_ref) references users(id) on delete cascade
       )
inherits (template_table_record_ref);
comment on table users_workflow is 'Workflow information for each record encoded';
comment on column users_workflow.user_ref is 'Reference of user - id field of users table';
comment on column users_workflow.table_ref is 'Reference of table concerned - id field of table_list table';
comment on column users_workflow.record_id is 'ID of record a workflow is defined for';
comment on column users_workflow.status is 'Record status: to correct, to be corrected or published';
comment on column users_workflow.modification_date_time is 'Date and time of status change - last date/time is used as actual status, but helps also to keep an history of status change';
comment on column users_workflow.comment is 'Complementary comments';
create table users_tables_fields_tracked
       (
        table_ref integer not null,
        field_ref integer not null,
        user_ref integer not null,
        constraint unq_users_tables_fields_tracked unique (table_ref, field_ref, user_ref),
        constraint fk_users_tables_fields_tracked_table_list foreign key (table_ref) references table_list(id),
        constraint fk_users_tables_fields_tracked_field_list foreign key (field_ref) references field_list(id),
        constraint fk_users_tables_fields_tracked_users foreign key (user_ref) references users(id) on delete cascade
       );
comment on table users_tables_fields_tracked is 'List fields tracked per user';
comment on column users_tables_fields_tracked.user_ref is 'Reference of user - id field of users table';
comment on column users_tables_fields_tracked.table_ref is 'Reference of table concerned - id field of table_list table';
comment on column users_tables_fields_tracked.field_ref is 'Reference of field tracked - id field of field_list table';
create table users_tracking
       (
        id bigserial not null,
        user_ref integer not null,
        action tracking_actions default 'insert' not null,
        modification_date_time update_date_time,
        constraint pk_users_tracking_pk primary key (id),
        constraint fk_users_tracking_table_list foreign key (table_ref) references table_list(id),
        constraint fk_users_tracking_users foreign key (user_ref) references users(id)
       )
inherits (template_table_record_ref);
comment on table users_tracking is 'Tracking of users actions on tables';
comment on column users_tracking.table_ref is 'Reference of table concerned - id field of table_list table';
comment on column users_tracking.record_id is 'ID of record concerned';
comment on column users_tracking.id is 'Unique identifier of a table track entry';
comment on column users_tracking.user_ref is 'Reference of user having made an action - id field of users table';
comment on column users_tracking.action is 'Action done on table record';
comment on column users_tracking.modification_date_time is 'Track date and time';
create table users_tracking_records
       (
        tracking_ref bigint not null,
        field_ref integer not null,
        old_value varchar[],
        new_value varchar[],
        constraint unq_users_tracking_records unique (tracking_ref, field_ref),
        constraint fk_users_tracking_records_users_tracking foreign key (tracking_ref) references users_tracking(id) on delete cascade,
        constraint fk_users_tracking_records_field_list foreign key (field_ref) references field_list(id)
       );
comment on table users_tracking_records is 'Track of fields modification per record';
comment on column users_tracking_records.tracking_ref is 'Reference of tracking entry - id field of users_tracking table';
comment on column users_tracking_records.field_ref is 'Reference of field concerned - id field of field_list table';
comment on column users_tracking_records.old_value is 'Old value when an update is done - array of values when field modified is an array';
comment on column users_tracking_records.new_value is 'New value when an update is done - array of values when field modified is an array';
create table collection_maintenance
       (
        user_ref integer not null,
        category maintenance_categories default 'action' not null,
        action_observation varchar not null,
        description varchar,
        description_ts tsvector,
        language_full_text full_text_language,
        modification_date_time update_date_time,
        constraint fk_collection_maintenance_table_list foreign key (table_ref) references table_list(id),
        constraint fk_collection_maintenance_users foreign key (user_ref) references users(id)
       )
inherits (template_table_record_ref);
comment on table collection_maintenance is 'History of specimen maintenance';
comment on column collection_maintenance.table_ref is 'Reference of table a maintenance entry has been created for';
comment on column collection_maintenance.record_id is 'ID of record a maintenance entry has been created for';
comment on column collection_maintenance.user_ref is 'Reference of user having done an action or an observation';
comment on column collection_maintenance.category is 'Action or Observation';
comment on column collection_maintenance.action_observation is 'Action or observation done';
comment on column collection_maintenance.description is 'Complementary description';
comment on column collection_maintenance.description_ts is 'tsvector form of description field';
comment on column collection_maintenance.language_full_text is 'Language used by to_tsvector full text search function';
create table my_saved_searches
       (
        user_ref integer not null,
        name varchar default 'default' not null,
        search_criterias varchar not null,
        favorite boolean default false not null,
        modification_date_time update_date_time,
        visible_fields_in_result varchar[] not null,
        constraint unq_my_saved_searches unique (user_ref, name),
        constraint fk_my_saved_searches_users foreign key (user_ref) references users(id) on delete cascade
       );
comment on table my_saved_searches is 'Stores user''s saved searches but also (by default) the last search done';
comment on column my_saved_searches.user_ref is 'Reference of user having saved a search';
comment on column my_saved_searches.name is 'Name given by user to his/her saved search';
comment on column my_saved_searches.search_criterias is 'String field containing the serialization of search criterias';
comment on column my_saved_searches.favorite is 'Flag telling if saved search concerned is one of the favorites or not';
comment on column my_saved_searches.modification_date_time is 'Last modification or entry date and time';
comment on column my_saved_searches.visible_fields_in_result is 'Array of fields that were set visible in the result table at the time the search was saved';
create table my_preferences
       (
        user_ref integer not null,
        category pref_categories default 'board_widget' not null,
        group_name varchar not null,
        order_by smallint default 1 not null,
        col_num smallint default 1 not null,
        mandatory boolean default false not null,
        visible boolean default true not null,
        opened boolean default true not null,
        color varchar default '#5BAABD' not null,
        icon_ref integer,
        title_perso varchar(32),
        constraint unq_my_preferences unique (user_ref, category, group_name),
        constraint fk_my_preferences_users foreign key (user_ref) references users(id) on delete cascade,
        constraint fk_my_preferences_multimedia foreign key (icon_ref) references multimedia(id)
       );
comment on table my_preferences is 'Stores user''s preferences for customizable page elements - widgets mainly';
comment on column my_preferences.user_ref is 'Reference of user concerned - id field of users table';
comment on column my_preferences.category is 'Customizable page element category: board widget, encoding widget,...';
comment on column my_preferences.group_name is 'Customizable page element name';
comment on column my_preferences.order_by is 'Absolute order by between page element name';
comment on column my_preferences.col_num is 'Column number - tells in which column the page element concerned is';
comment on column my_preferences.mandatory is 'Flag telling if the page element can be closed or not';
comment on column my_preferences.visible is 'Flag telling if the page element is on the board or in the widget chooser';
comment on column my_preferences.opened is 'Flag telling if the page element is opened by default or not';
comment on column my_preferences.color is 'Color given to page element by user';
comment on column my_preferences.icon_ref is 'Reference of multimedia icon to be used before page element title';
comment on column my_preferences.title_perso is 'Page element title given by user';
create table my_saved_specimens
       (
        user_ref integer not null,
        name varchar not null,
        specimen_ids integer[] not null,
        favorite boolean default false not null,
        modification_date_time update_date_time,
        constraint unq_my_saved_specimens unique (user_ref, name),
        constraint fk_my_saved_specimens_users foreign key (user_ref) references users(id) on delete cascade
       );
comment on table my_saved_specimens is 'List of specimens selection made by users - sort of suitcases for personal selections';
comment on column my_saved_specimens.user_ref is 'Reference of user - id field of users table';
comment on column my_saved_specimens.name is 'Name given to this selection by user';
comment on column my_saved_specimens.specimen_ids is 'Array of ids of all specimens selected';
comment on column my_saved_specimens.favorite is 'Flag telling the selection is one of the favorites or not';
comment on column my_saved_specimens.modification_date_time is 'Last update date and time';
create table template_classifications
       (
        id serial not null,
        name varchar not null,
        name_indexed varchar not null,
        description_year smallint,
        description_year_compl char(2),
        level_ref integer not null,
        status status default 'valid' not null,
        full_hierarchy_path varchar not null,
        partial_hierarchy_path varchar not null
       );
comment on table template_classifications is 'Template table used to construct every common data in each classifications tables (taxonomy, chronostratigraphy, lithostratigraphy,...)';
comment on column template_classifications.id is 'Unique identifier of a classification unit';
comment on column template_classifications.name is 'Classification unit name';
comment on column template_classifications.name_indexed is 'Indexed form of name field';
comment on column template_classifications.description_year is 'Year of description';
comment on column template_classifications.description_year_compl is 'Complement to year of description: a, b, c, ...';
comment on column template_classifications.level_ref is 'Reference of classification level the unit is encoded in';
comment on column template_classifications.status is 'Validitiy status: valid, invalid, in discussion';
comment on column template_classifications.full_hierarchy_path is 'Hierarchy path composed of parents ids and unit name - used for unique indexation';
comment on column template_classifications.partial_hierarchy_path is 'Partial hierarchy path composed of non 0 parents ids -> reflexion of real hierarchy path for treeview ease of construction';
create table taxa
       (
        domain_ref classifications_ids,
        domain_indexed classifications_names,
        kingdom_ref classifications_ids,
        kingdom_indexed classifications_names,
        super_phylum_ref classifications_ids,
        super_phylum_indexed classifications_names,
        phylum_ref classifications_ids,
        phylum_indexed classifications_names,
        sub_phylum_ref classifications_ids,
        sub_phylum_indexed classifications_names,
        infra_phylum_ref classifications_ids,
        infra_phylum_indexed classifications_names,
        super_cohort_botany_ref classifications_ids,
        super_cohort_botany_indexed classifications_names,
        cohort_botany_ref classifications_ids,
        cohort_botany_indexed classifications_names,
        sub_cohort_botany_ref classifications_ids,
        sub_cohort_botany_indexed classifications_names,
        infra_cohort_botany_ref classifications_ids,
        infra_cohort_botany_indexed classifications_names,
        super_class_ref classifications_ids,
        super_class_indexed classifications_names,
        class_ref classifications_ids,
        class_indexed classifications_names,
        sub_class_ref classifications_ids,
        sub_class_indexed classifications_names,
        infra_class_ref classifications_ids,
        infra_class_indexed classifications_names,
        super_division_ref classifications_ids,
        super_division_indexed classifications_names,
        division_ref classifications_ids,
        division_indexed classifications_names,
        sub_division_ref classifications_ids,
        sub_division_indexed classifications_names,
        infra_division_ref classifications_ids,
        infra_division_indexed classifications_names,
        super_legion_ref classifications_ids,
        super_legion_indexed classifications_names,
        legion_ref classifications_ids,
        legion_indexed classifications_names,
        sub_legion_ref classifications_ids,
        sub_legion_indexed classifications_names,
        infra_legion_ref classifications_ids,
        infra_legion_indexed classifications_names,
        super_cohort_zool_ref classifications_ids,
        super_cohort_zool_indexed classifications_names,
        cohort_zool_ref classifications_ids,
        cohort_zool_indexed classifications_names,
        sub_cohort_zool_ref classifications_ids,
        sub_cohort_zool_indexed classifications_names,
        infra_cohort_zool_ref classifications_ids,
        infra_cohort_zool_indexed classifications_names,
        super_order_ref classifications_ids,
        super_order_indexed classifications_names,
        order_ref classifications_ids,
        order_indexed classifications_names,
        sub_order_ref classifications_ids,
        sub_order_indexed classifications_names,
        infra_order_ref classifications_ids,
        infra_order_indexed classifications_names,
        section_zool_ref classifications_ids,
        section_zool_indexed classifications_names,
        sub_section_zool_ref classifications_ids,
        sub_section_zool_indexed classifications_names,
        super_family_ref classifications_ids,
        super_family_indexed classifications_names,
        family_ref classifications_ids,
        family_indexed classifications_names,
        sub_family_ref classifications_ids,
        sub_family_indexed classifications_names,
        infra_family_ref classifications_ids,
        infra_family_indexed classifications_names,
        super_tribe_ref classifications_ids,
        super_tribe_indexed classifications_names,
        tribe_ref classifications_ids,
        tribe_indexed classifications_names,
        sub_tribe_ref classifications_ids,
        sub_tribe_indexed classifications_names,
        infra_tribe_ref classifications_ids,
        infra_tribe_indexed classifications_names,
        genus_ref classifications_ids,
        genus_indexed classifications_names,
        sub_genus_ref classifications_ids,
        sub_genus_indexed classifications_names,
        section_botany_ref classifications_ids,
        section_botany_indexed classifications_names,
        sub_section_botany_ref classifications_ids,
        sub_section_botany_indexed classifications_names,
        serie_ref classifications_ids,
        serie_indexed classifications_names,
        sub_serie_ref classifications_ids,
        sub_serie_indexed classifications_names,
        super_species_ref classifications_ids,
        super_species_indexed classifications_names,
        species_ref classifications_ids,
        species_indexed classifications_names,
        sub_species_ref classifications_ids,
        sub_species_indexed classifications_names,
        variety_ref classifications_ids,
        variety_indexed classifications_names,
        sub_variety_ref classifications_ids,
        sub_variety_indexed classifications_names,
        form_ref classifications_ids,
        form_indexed classifications_names,
        sub_form_ref classifications_ids,
        sub_form_indexed classifications_names,
        abberans_ref classifications_ids,
        abberans_indexed classifications_names,
        chimera_hybrid_pos varchar default 'none' not null,
        extinct boolean default false not null,
        constraint pk_taxa primary key (id),
        constraint unq_taxa unique (full_hierarchy_path),
        constraint fk_taxa_catalogue_levels_fk foreign key (level_ref) references catalogue_levels(id),
        constraint fk_taxa_taxa_domain foreign key (domain_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_kingdom_taxa foreign key (kingdom_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_super_phylum_taxa foreign key (super_phylum_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_phylum_taxa foreign key (phylum_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_phylum_taxa foreign key (sub_phylum_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_infra_phylum_taxa foreign key (infra_phylum_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_super_cohort_botany_taxa foreign key (super_cohort_botany_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_cohort_botany_taxa foreign key (cohort_botany_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_cohort_botany_taxa foreign key (sub_cohort_botany_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_infra_cohort_botany_taxa foreign key (infra_cohort_botany_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_super_class_taxa foreign key (super_class_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_class_taxa foreign key (class_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_class_taxa foreign key (sub_class_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_infra_class_taxa foreign key (infra_class_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_super_division_taxa foreign key (super_division_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_division_taxa foreign key (division_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_division_taxa foreign key (sub_division_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_infra_division_taxa foreign key (infra_division_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_super_legion_taxa foreign key (super_legion_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_legion_taxa foreign key (legion_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_legion_taxa foreign key (sub_legion_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_infra_legion_taxa foreign key (infra_legion_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_super_cohort_zool_taxa foreign key (super_cohort_zool_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_cohort_zool_taxa foreign key (cohort_zool_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_cohort_zool_taxa foreign key (sub_cohort_zool_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_infra_cohort_zool_taxa foreign key (infra_cohort_zool_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_super_order_taxa foreign key (super_order_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_order_taxa foreign key (order_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_order_taxa foreign key (sub_order_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_infra_order_taxa foreign key (infra_order_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_section_zool_taxa foreign key (section_zool_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_section_zool_taxa foreign key (sub_section_zool_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_super_family_taxa foreign key (super_family_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_family_taxa foreign key (family_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_family_taxa foreign key (sub_family_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_infra_family_taxa foreign key (infra_family_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_super_tribe_taxa foreign key (super_tribe_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_tribe_taxa foreign key (tribe_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_tribe_taxa foreign key (sub_tribe_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_infra_tribe_taxa foreign key (infra_tribe_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_genus_taxa foreign key (genus_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_genus_taxa foreign key (sub_genus_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_section_botany_taxa foreign key (section_botany_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_section_botany_taxa foreign key (sub_section_botany_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_serie_taxa foreign key (serie_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_serie_taxa foreign key (sub_serie_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_super_species_taxa foreign key (super_species_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_species_taxa foreign key (species_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_species_taxa foreign key (sub_species_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_variety_taxa foreign key (variety_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_variety_taxa foreign key (sub_variety_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_form_taxa foreign key (form_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_sub_form_taxa foreign key (sub_form_ref) references taxa(id) on delete cascade,
        constraint fk_taxa_abberans_taxa foreign key (abberans_ref) references taxa(id) on delete cascade
       )
inherits (template_classifications);
comment on table taxa is 'Taxonomic classification table';
comment on column taxa.id is 'Unique identifier of a classification unit';
comment on column taxa.name is 'Classification unit name';
comment on column taxa.name_indexed is 'Indexed form of name field';
comment on column taxa.description_year is 'Year of description';
comment on column taxa.description_year_compl is 'Complement to year of description: a, b, c, ...';
comment on column taxa.level_ref is 'Reference of classification level the unit is encoded in';
comment on column taxa.status is 'Validitiy status: valid, invalid, in discussion';
comment on column taxa.full_hierarchy_path is 'Hierarchy path composed of parents ids and unit name - used for unique indexation';
comment on column taxa.partial_hierarchy_path is 'Partial hierarchy path composed of non 0 parents ids -> reflexion of real hierarchy path for treeview ease of construction';
comment on column taxa.domain_ref is 'Reference of domain the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.domain_indexed is 'Indexed name of domain the current taxa depends of';
comment on column taxa.kingdom_ref is 'Reference of kingdom the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.kingdom_indexed is 'Indexed name of kingdom the current taxa depends of';
comment on column taxa.super_phylum_ref is 'Reference of super_phylum the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.super_phylum_indexed is 'Indexed name of super_phylum the current taxa depends of';
comment on column taxa.phylum_ref is 'Reference of phylum the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.phylum_indexed is 'Indexed name of phylum the current taxa depends of';
comment on column taxa.sub_phylum_ref is 'Reference of sub phylum the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_phylum_indexed is 'Indexed name of sub phylum the current taxa depends of';
comment on column taxa.infra_phylum_ref is 'Reference of infra phylum the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.infra_phylum_indexed is 'Indexed name of infra phylum the current taxa depends of';
comment on column taxa.super_cohort_botany_ref is 'Reference of super cohort botany the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.super_cohort_botany_indexed is 'Indexed name of super cohort botany the current taxa depends of';
comment on column taxa.cohort_botany_ref is 'Reference of cohort botany the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.cohort_botany_indexed is 'Indexed name of cohort botany the current taxa depends of';
comment on column taxa.sub_cohort_botany_ref is 'Reference of sub cohort botany the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_cohort_botany_indexed is 'Indexed name of sub cohort botany the current taxa depends of';
comment on column taxa.infra_cohort_botany_ref is 'Reference of infra cohort botany the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.infra_cohort_botany_indexed is 'Indexed name of infra cohort botany the current taxa depends of';
comment on column taxa.super_class_ref is 'Reference of super class the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.super_class_indexed is 'Indexed name of super class the current taxa depends of';
comment on column taxa.class_ref is 'Reference of class the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.class_indexed is 'Indexed name of class the current taxa depends of';
comment on column taxa.sub_class_ref is 'Reference of sub class the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_class_indexed is 'Indexed name of sub class the current taxa depends of';
comment on column taxa.infra_class_ref is 'Reference of infra class the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.infra_class_indexed is 'Indexed name of infra class the current taxa depends of';
comment on column taxa.super_division_ref is 'Reference of super division the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.super_division_indexed is 'Indexed name of super division the current taxa depends of';
comment on column taxa.division_ref is 'Reference of division the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.division_indexed is 'Indexed name of division the current taxa depends of';
comment on column taxa.sub_division_ref is 'Reference of sub division the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_division_indexed is 'Indexed name of sub division the current taxa depends of';
comment on column taxa.infra_division_ref is 'Reference of infra division the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.infra_division_indexed is 'Indexed name of infra division the current taxa depends of';
comment on column taxa.super_legion_ref is 'Reference of super legion the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.super_legion_indexed is 'Indexed name of super legion the current taxa depends of';
comment on column taxa.legion_ref is 'Reference of legion the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.legion_indexed is 'Indexed name of legion the current taxa depends of';
comment on column taxa.sub_legion_ref is 'Reference of sub legion the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_legion_indexed is 'Indexed name of sub legion the current taxa depends of';
comment on column taxa.infra_legion_ref is 'Reference of infra legion the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.infra_legion_indexed is 'Indexed name of infra legion the current taxa depends of';
comment on column taxa.super_cohort_zool_ref is 'Reference of super cohort zool the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.super_cohort_zool_indexed is 'Indexed name of super cohort zool the current taxa depends of';
comment on column taxa.cohort_zool_ref is 'Reference of cohort zool the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.cohort_zool_indexed is 'Indexed name of cohort zool the current taxa depends of';
comment on column taxa.sub_cohort_zool_ref is 'Reference of sub cohort zool the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_cohort_zool_indexed is 'Indexed name of sub cohort zool the current taxa depends of';
comment on column taxa.infra_cohort_zool_ref is 'Reference of infra cohort zool the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.infra_cohort_zool_indexed is 'Indexed name of infra cohort zool the current taxa depends of';
comment on column taxa.super_order_ref is 'Reference of super order the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.super_order_indexed is 'Indexed name of super order the current taxa depends of';
comment on column taxa.order_ref is 'Reference of order the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.order_indexed is 'Indexed name of order the current taxa depends of';
comment on column taxa.sub_order_ref is 'Reference of sub order the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_order_indexed is 'Indexed name of sub order the current taxa depends of';
comment on column taxa.infra_order_ref is 'Reference of infra order the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.infra_order_indexed is 'Indexed name of infra order the current taxa depends of';
comment on column taxa.section_zool_ref is 'Reference of section zool the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.section_zool_indexed is 'Indexed name of section zool the current taxa depends of';
comment on column taxa.sub_section_zool_ref is 'Reference of sub section zool the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_section_zool_indexed is 'Indexed name of sub section zool the current taxa depends of';
comment on column taxa.super_family_ref is 'Reference of super family the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.super_family_indexed is 'Indexed name of super family the current taxa depends of';
comment on column taxa.family_ref is 'Reference of family the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.family_indexed is 'Indexed name of family the current taxa depends of';
comment on column taxa.sub_family_ref is 'Reference of sub family the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_family_indexed is 'Indexed name of sub family the current taxa depends of';
comment on column taxa.infra_family_ref is 'Reference of infra family the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.infra_family_indexed is 'Indexed name of infra family the current taxa depends of';
comment on column taxa.super_tribe_ref is 'Reference of super tribe the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.super_tribe_indexed is 'Indexed name of super tribe the current taxa depends of';
comment on column taxa.tribe_ref is 'Reference of tribe the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.tribe_indexed is 'Indexed name of tribe the current taxa depends of';
comment on column taxa.sub_tribe_ref is 'Reference of sub tribe the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_tribe_indexed is 'Indexed name of sub tribe the current taxa depends of';
comment on column taxa.infra_tribe_ref is 'Reference of infra tribe the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.infra_tribe_indexed is 'Indexed name of infra tribe the current taxa depends of';
comment on column taxa.genus_ref is 'Reference of genus the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.genus_indexed is 'Indexed name of genus the current taxa depends of';
comment on column taxa.sub_genus_ref is 'Reference of sub genus the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_genus_indexed is 'Indexed name of sub genus the current taxa depends of';
comment on column taxa.section_botany_ref is 'Reference of section botany the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.section_botany_indexed is 'Indexed name of section botany the current taxa depends of';
comment on column taxa.sub_section_botany_ref is 'Reference of sub section botany the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_section_botany_indexed is 'Indexed name of sub section botany the current taxa depends of';
comment on column taxa.serie_ref is 'Reference of series the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.serie_indexed is 'Indexed name of series the current taxa depends of';
comment on column taxa.sub_serie_ref is 'Reference of sub series the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_serie_indexed is 'Indexed name of sub series the current taxa depends of';
comment on column taxa.super_species_ref is 'Reference of super species the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.super_species_indexed is 'Indexed name of super species the current taxa depends of';
comment on column taxa.species_ref is 'Reference of species the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.species_indexed is 'Indexed name of species the current taxa depends of';
comment on column taxa.sub_species_ref is 'Reference of sub species the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_species_indexed is 'Indexed name of sub species the current taxa depends of';
comment on column taxa.variety_ref is 'Reference of variety the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.variety_indexed is 'Indexed name of variety the current taxa depends of';
comment on column taxa.sub_variety_ref is 'Reference of sub variety the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_variety_indexed is 'Indexed name of sub variety the current taxa depends of';
comment on column taxa.form_ref is 'Reference of form the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.form_indexed is 'Indexed name of form the current taxa depends of';
comment on column taxa.sub_form_ref is 'Reference of sub form the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.sub_form_indexed is 'Indexed name of sub form the current taxa depends of';
comment on column taxa.abberans_ref is 'Reference of abberans the current taxa depends of - id field of taxa table - recursive reference';
comment on column taxa.abberans_indexed is 'Indexed name of abberans the current taxa depends of';
comment on column taxa.chimera_hybrid_pos is 'Chimera or Hybrid informations';
comment on column taxa.extinct is 'Tells if taxa is extinct or not';
create table people_taxonomic_names
       (
        person_ref integer not null,
        taxonomic_top_ref integer default 0 not null,
        person_name varchar not null,
        constraint unq_people_taxonomic_names unique (person_ref, taxonomic_top_ref, person_name),
        constraint fk_people_taxonomic_names_taxa foreign key (taxonomic_top_ref) references taxa(id) on delete cascade,
        constraint fk_people_taxonomic_names_people foreign key (person_ref) references people(id) on delete cascade
       );
comment on table people_taxonomic_names is 'Name translation depending on taxonomic top group studied: in botany, Liné will be written L. and in zoology, Liné will be written Linaeus';
comment on column people_taxonomic_names.person_ref is 'Reference of the person concerned - id field of people table';
comment on column people_taxonomic_names.taxonomic_top_ref is 'Reference of the top taxonomic group concerned - id field of taxa table';
comment on column people_taxonomic_names.person_name is 'Person name for the group concerned';
create table chronostratigraphy
       (
        eon_ref classifications_ids,
        eon_indexed classifications_names,
        era_ref classifications_ids,
        era_indexed classifications_names,
        sub_era_ref classifications_ids,
        sub_era_indexed classifications_names,
        system_ref classifications_ids,
        system_indexed classifications_names,
        serie_ref classifications_ids,
        serie_indexed classifications_names,
        stage_ref classifications_ids,
        stage_indexed classifications_names,
        sub_stage_ref classifications_ids,
        sub_stage_indexed classifications_names,
        sub_level_1_ref classifications_ids,
        sub_level_1_indexed classifications_names,
        sub_level_2_ref classifications_ids,
        sub_level_2_indexed classifications_names,
        lower_bound numeric,
        upper_bound numeric,
        constraint pk_chronostratigraphy primary key (id),
        constraint unq_chronostratigraphy unique (full_hierarchy_path),
        constraint fk_chronostratigraphy_catalogue_levels foreign key (level_ref) references catalogue_levels(id),
        constraint fk_chronostratigraphy_eon_chronostratigraphy foreign key (eon_ref) references chronostratigraphy(id) on delete cascade,
        constraint fk_chronostratigraphy_era_chronostratigraphy foreign key (era_ref) references chronostratigraphy(id) on delete cascade,
        constraint fk_chronostratigraphy_sub_era_chronostratigraphy foreign key (sub_era_ref) references chronostratigraphy(id) on delete cascade,
        constraint fk_chronostratigraphy_system_chronostratigraphy foreign key (system_ref) references chronostratigraphy(id) on delete cascade,
        constraint fk_chronostratigraphy_serie_chronostratigraphy foreign key (serie_ref) references chronostratigraphy(id) on delete cascade,
        constraint fk_chronostratigraphy_stage_chronostratigraphy foreign key (stage_ref) references chronostratigraphy(id) on delete cascade,
        constraint fk_chronostratigraphy_sub_stage_chronostratigraphy foreign key (sub_stage_ref) references chronostratigraphy(id) on delete cascade,
        constraint fk_chronostratigraphy_sub_level_1_chronostratigraphy foreign key (sub_level_1_ref) references chronostratigraphy(id) on delete cascade,
        constraint fk_chronostratigraphy_sub_level_2_chronostratigraphy foreign key (sub_level_2_ref) references chronostratigraphy(id) on delete cascade
       )
inherits (template_classifications);
comment on table chronostratigraphy is 'List of chronostratigraphic units';
comment on column chronostratigraphy.id is 'Unique identifier of a classification unit';
comment on column chronostratigraphy.name is 'Classification unit name';
comment on column chronostratigraphy.name_indexed is 'Indexed form of name field';
comment on column chronostratigraphy.description_year is 'Year of description';
comment on column chronostratigraphy.description_year_compl is 'Complement to year of description: a, b, c, ...';
comment on column chronostratigraphy.level_ref is 'Reference of classification level the unit is encoded in';
comment on column chronostratigraphy.status is 'Validitiy status: valid, invalid, in discussion';
comment on column chronostratigraphy.full_hierarchy_path is 'Hierarchy path composed of parents ids and unit name - used for unique indexation';
comment on column chronostratigraphy.partial_hierarchy_path is 'Partial hierarchy path composed of non 0 parents ids -> reflexion of real hierarchy path for treeview ease of construction';
comment on column chronostratigraphy.eon_ref is 'Reference of eon the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column chronostratigraphy.eon_indexed is 'Indexed name of eon the current unit depends of';
comment on column chronostratigraphy.era_ref is 'Reference of era the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column chronostratigraphy.era_indexed is 'Indexed name of era the current unit depends of';
comment on column chronostratigraphy.sub_era_ref is 'Reference of sub era the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column chronostratigraphy.sub_era_indexed is 'Indexed name of sub era the current unit depends of';
comment on column chronostratigraphy.system_ref is 'Reference of system the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column chronostratigraphy.system_indexed is 'Indexed name of system the current unit depends of';
comment on column chronostratigraphy.serie_ref is 'Reference of serie the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column chronostratigraphy.serie_indexed is 'Indexed name of serie the current unit depends of';
comment on column chronostratigraphy.stage_ref is 'Reference of stage the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column chronostratigraphy.stage_indexed is 'Indexed name of stage the current unit depends of';
comment on column chronostratigraphy.sub_stage_ref is 'Reference of sub stage the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column chronostratigraphy.sub_stage_indexed is 'Indexed name of sub stage the current unit depends of';
comment on column chronostratigraphy.sub_level_1_ref is 'Reference of sub level the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column chronostratigraphy.sub_level_1_indexed is 'Indexed name of sub level the current unit depends of';
comment on column chronostratigraphy.sub_level_2_ref is 'Reference of sub level the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column chronostratigraphy.sub_level_2_indexed is 'Indexed name of sub level the current unit depends of';
comment on column chronostratigraphy.lower_bound is 'Lower age boundary in years';
comment on column chronostratigraphy.upper_bound is 'Upper age boundary in years';
create table lithostratigraphy
       (
        group_ref classifications_ids,
        group_indexed classifications_names,
        formation_ref classifications_ids,
        formation_indexed classifications_names,
        member_ref classifications_ids,
        member_indexed classifications_names,
        layer_ref classifications_ids,
        layer_indexed classifications_names,
        sub_level_1_ref classifications_ids,
        sub_level_1_indexed classifications_names,
        sub_level_2_ref classifications_ids,
        sub_level_2_indexed classifications_names,
        constraint pk_lithostratigraphy primary key (id),
        constraint unq_lithostratigraphy unique (full_hierarchy_path),
        constraint fk_lithostratigraphy_catalogue_levels foreign key (level_ref) references catalogue_levels(id),
        constraint fk_lithostratigraphy_group_lithostratigraphy foreign key (group_ref) references lithostratigraphy(id) on delete cascade,
        constraint fk_lithostratigraphy_formation_lithostratigraphy foreign key (formation_ref) references lithostratigraphy(id) on delete cascade,
        constraint fk_lithostratigraphy_member_lithostratigraphy foreign key (member_ref) references lithostratigraphy(id) on delete cascade,
        constraint fk_lithostratigraphy_layer_lithostratigraphy foreign key (layer_ref) references lithostratigraphy(id) on delete cascade,
        constraint fk_lithostratigraphy_sub_level_1_lithostratigraphy foreign key (sub_level_1_ref) references lithostratigraphy(id) on delete cascade,
        constraint fk_lithostratigraphy_sub_level_2_lithostratigraphy foreign key (sub_level_2_ref) references lithostratigraphy(id) on delete cascade
       )
inherits (template_classifications);
comment on table lithostratigraphy is 'List of lithostratigraphic units';
comment on column lithostratigraphy.id is 'Unique identifier of a classification unit';
comment on column lithostratigraphy.name is 'Classification unit name';
comment on column lithostratigraphy.name_indexed is 'Indexed form of name field';
comment on column lithostratigraphy.description_year is 'Year of description';
comment on column lithostratigraphy.description_year_compl is 'Complement to year of description: a, b, c, ...';
comment on column lithostratigraphy.level_ref is 'Reference of classification level the unit is encoded in';
comment on column lithostratigraphy.status is 'Validitiy status: valid, invalid, in discussion';
comment on column lithostratigraphy.full_hierarchy_path is 'Hierarchy path composed of parents ids and unit name - used for unique indexation';
comment on column lithostratigraphy.partial_hierarchy_path is 'Partial hierarchy path composed of non 0 parents ids -> reflexion of real hierarchy path for treeview ease of construction';
comment on column lithostratigraphy.group_ref is 'Reference of group the current unit depends of - id field of lithostratigraphy table - recursive reference';
comment on column lithostratigraphy.group_indexed is 'Indexed name of group the current unit depends of';
comment on column lithostratigraphy.formation_ref is 'Reference of formation the current unit depends of - id field of lithostratigraphy table - recursive reference';
comment on column lithostratigraphy.formation_indexed is 'Indexed name of formation the current unit depends of';
comment on column lithostratigraphy.member_ref is 'Reference of member the current unit depends of - id field of lithostratigraphy table - recursive reference';
comment on column lithostratigraphy.member_indexed is 'Indexed name of member the current unit depends of';
comment on column lithostratigraphy.layer_ref is 'Reference of layer the current unit depends of - id field of lithostratigraphy table - recursive reference';
comment on column lithostratigraphy.layer_indexed is 'Indexed name of layer the current unit depends of';
comment on column lithostratigraphy.sub_level_1_ref is 'Reference of sub level the current unit depends of - id field of lithostratigraphy table - recursive reference';
comment on column lithostratigraphy.sub_level_1_indexed is 'Indexed name of sub level the current unit depends of';
comment on column lithostratigraphy.sub_level_2_ref is 'Reference of sub level the current unit depends of - id field of lithostratigraphy table - recursive reference';
comment on column lithostratigraphy.sub_level_2_indexed is 'Indexed name of sub level the current unit depends of';
create table mineralogy
       (
        code varchar not null,
        classification mineralogy_classifications default 'strunz' not null,
        formule varchar,
        formule_indexed varchar,
        cristal_system cristal_systems,
        unit_class_ref classifications_ids,
        unit_class_indexed classifications_names,
        unit_division_ref classifications_ids,
        unit_division_indexed classifications_names,
        unit_family_ref classifications_ids,
        unit_family_indexed classifications_names,
        unit_group_ref classifications_ids,
        unit_group_indexed classifications_names,
        unit_variety_ref classifications_ids,
        unit_variety_indexed classifications_names,
        constraint pk_mineralogy primary key (id),
        constraint unq_mineralogy unique (full_hierarchy_path),
        constraint fk_mineralogy_catalogue_levels foreign key (level_ref) references catalogue_levels(id),
        constraint fk_mineralogy_unit_class_mineralogy foreign key (unit_class_ref) references mineralogy(id) on delete cascade,
        constraint fk_mineralogy_unit_division_mineralogy foreign key (unit_division_ref) references mineralogy(id) on delete cascade,
        constraint fk_mineralogy_unit_family_mineralogy foreign key (unit_family_ref) references mineralogy(id) on delete cascade,
        constraint fk_mineralogy_unit_group_mineralogy foreign key (unit_group_ref) references mineralogy(id) on delete cascade,
        constraint fk_mineralogy_unit_variety_mineralogy foreign key (unit_variety_ref) references mineralogy(id) on delete cascade
       )
inherits (template_classifications);
comment on table mineralogy is 'List of mineralogic units';
comment on column mineralogy.id is 'Unique identifier of a classification unit';
comment on column mineralogy.name is 'Classification unit name';
comment on column mineralogy.name_indexed is 'Indexed form of name field';
comment on column mineralogy.description_year is 'Year of description';
comment on column mineralogy.description_year_compl is 'Complement to year of description: a, b, c, ...';
comment on column mineralogy.level_ref is 'Reference of classification level the unit is encoded in';
comment on column mineralogy.status is 'Validitiy status: valid, invalid, in discussion';
comment on column mineralogy.full_hierarchy_path is 'Hierarchy path composed of parents ids and unit name - used for unique indexation';
comment on column mineralogy.partial_hierarchy_path is 'Partial hierarchy path composed of non 0 parents ids -> reflexion of real hierarchy path for treeview ease of construction';
comment on column mineralogy.code is 'Classification code given to mineral - in classification chosen - Strunz by default';
comment on column mineralogy.classification is 'Classification system used to describe mineral: strunz, dana,...';
comment on column mineralogy.formule is 'Chemical formulation';
comment on column mineralogy.formule_indexed is 'Indexed form of foumule field';
comment on column mineralogy.cristal_system is 'Cristal system defining the mineral structure: isometric, hexagonal,...';
comment on column mineralogy.unit_class_ref is 'Reference of class the current unit depends of - id field of mineralogy table - recursive reference';
comment on column mineralogy.unit_class_indexed is 'Indexed name of class the current unit depends of';
comment on column mineralogy.unit_division_ref is 'Reference of division the current unit depends of - id field of mineralogy table - recursive reference';
comment on column mineralogy.unit_division_indexed is 'Indexed name of division the current unit depends of';
comment on column mineralogy.unit_family_ref is 'Reference of family the current unit depends of - id field of mineralogy table - recursive reference';
comment on column mineralogy.unit_family_indexed is 'Indexed name of family the current unit depends of';
comment on column mineralogy.unit_group_ref is 'Reference of group the current unit depends of - id field of mineralogy table - recursive reference';
comment on column mineralogy.unit_group_indexed is 'Indexed name of group the current unit depends of';
comment on column mineralogy.unit_variety_ref is 'Reference of sub level the current unit depends of - id field of mineralogy table - recursive reference';
comment on column mineralogy.unit_variety_indexed is 'Indexed name of sub level the current unit depends of';
create table lithology
       (
        constraint pk_lithology primary key (id),
        constraint unq_lithology unique (full_hierarchy_path),
        constraint fk_lithology_catalogue_levels foreign key (level_ref) references catalogue_levels(id)
       )
inherits (template_classifications);
comment on table lithology is 'List of mineralogic units';
comment on column lithology.id is 'Unique identifier of a classification unit';
comment on column lithology.name is 'Classification unit name';
comment on column lithology.name_indexed is 'Indexed form of name field';
comment on column lithology.description_year is 'Year of description';
comment on column lithology.description_year_compl is 'Complement to year of description: a, b, c, ...';
comment on column lithology.level_ref is 'Reference of classification level the unit is encoded in';
comment on column lithology.status is 'Validitiy status: valid, invalid, in discussion';
comment on column lithology.full_hierarchy_path is 'Hierarchy path composed of parents ids and unit name - used for unique indexation';
comment on column lithology.partial_hierarchy_path is 'Partial hierarchy path composed of non 0 parents ids -> reflexion of real hierarchy path for treeview ease of construction';
create table habitats
       (
        id serial not null,
        code varchar not null,
        description varchar not null,
        description_ts tsvector not null,
        description_language_full_text full_text_language, 
        habitat_system habitat_systems default 'eunis' not null,
        constraint pk_habitats primary key (id),
        constraint unq_habitats unique (code, habitat_system)
       );
comment on table habitats is 'Habitats classifications';
comment on column habitats.id is 'Unique identifier of a habitat';
comment on column habitats.code is 'Code given to this habitat in the classification encoded';
comment on column habitats.description is 'General description of the habitat';
comment on column habitats.description_ts is 'Indexed form of description field ready to be used with to_tsvector full text search function';
comment on column habitats.description_language_full_text is 'Language used to compose the description_ts tsvector field';
comment on column habitats.habitat_system is 'System used to describe habitat encoded';
create table multimedia_keywords
       (
        object_ref integer not null,
        keyword varchar not null,
        keyword_indexed varchar not null,
        constraint unq_multimedia_keywords unique (object_ref, keyword_indexed),
        constraint fk_multimedia_keywords_multimedia foreign key (object_ref) references multimedia(id) on delete cascade
       );
comment on table multimedia_keywords is 'List of keywords associated to a multimedia object - encoded in the keywords field on the interface';
comment on column multimedia_keywords.object_ref is 'Reference of multimedia object concerned';
comment on column multimedia_keywords.keyword is 'Keyword associated';
comment on column multimedia_keywords.keyword_indexed is 'Indexed form of keyword field';
create table soortenregister
       (
        taxa_ref integer default 0 not null,
        gtu_ref integer default 0 not null,
        habitat_ref integer default 0 not null,
        date_from date,
        date_to date,
        constraint fk_soortenregister_taxa foreign key (taxa_ref) references taxa(id) on delete cascade,
        constraint fk_soortenregister_gtu foreign key (gtu_ref) references gtu(id) on delete cascade,
        constraint fk_soortenregister_habitats foreign key (habitat_ref) references habitats(id) on delete cascade
       );
comment on table soortenregister is 'Species register table - Indicates the presence of a certain species in a certain habitat at a given place from time to time';
comment on column soortenregister.taxa_ref is 'Reference of taxon concerned - id field of taxa table';
comment on column soortenregister.gtu_ref is 'Reference of gtu concerned - id field of gtu table';
comment on column soortenregister.habitat_ref is 'Reference of habitat concerned - id field of habitats table';
comment on column soortenregister.date_from is 'From date association definition';
comment on column soortenregister.date_to is 'To date association definition';
create table specimens
       (
        id serial not null,
        collection_ref integer not null,
        expedition_ref integer,
        gtu_ref integer default 0 not null,
        taxon_ref integer default 0 not null,
        litho_ref integer default 0 not null,
        chrono_ref integer default 0 not null,
        lithology_ref integer default 0 not null,
        mineral_ref integer default 0 not null,
        identification_qual ident_qualifiers,
        identification_taxon_ref integer default 0 not null,
        host_taxon_ref integer default 0 not null,
        host_specimen_ref integer,
        host_relationship varchar,
        acquisition_category acquisition_categories default 'expedition' not null,
        acquisition_date_day date_day,
        acquisition_date_month date_month,
        acquisition_date_year date_year,
        collecting_method varchar,
        collecting_tool varchar,
        specimen_count_min integer default 1 not null,
        specimen_count_max integer default 1 not null,
        station_visible boolean default true not null,
        multimedia_visible boolean default true not null,
        category specimen_categories default 'physical' not null,
        constraint pk_specimens primary key (id),
        constraint specimens_expeditions_fk foreign key (expedition_ref) references expeditions(id),
        constraint unq_specimens unique (category, collection_ref, gtu_ref, taxon_ref, litho_ref, chrono_ref, lithology_ref, mineral_ref, identification_taxon_ref, host_taxon_ref),
        constraint fk_specimens_gtu foreign key (gtu_ref) references gtu(id) on delete set default,
        constraint fk_specimens_collections foreign key (collection_ref) references collections(id) on delete set default,
        constraint fk_specimens_taxa foreign key (taxon_ref) references taxa(id) on delete set default,
        constraint fk_specimens_lithostratigraphy foreign key (litho_ref) references lithostratigraphy(id) on delete set default,
        constraint fk_specimens_lithology foreign key (lithology_ref) references lithology(id) on delete set default,
        constraint fk_specimens_mineralogy foreign key (mineral_ref) references mineralogy(id) on delete set default,
        constraint fk_specimens_chronostratigraphy foreign key (chrono_ref) references chronostratigraphy(id) on delete set default,
        constraint fk_specimens_ident_taxa foreign key (identification_taxon_ref) references taxa(id) on delete set default,
        constraint fk_specimens_host_taxa foreign key (host_taxon_ref) references taxa(id) on delete set default,
        constraint fk_specimens_host_specimen foreign key (host_specimen_ref) references specimens(id) on delete set null,
	constraint chk_chk_specimens_minmax check (specimen_count_min <= specimen_count_max)
       );
comment on table specimens is 'Specimens or batch of specimens stored in collection';
comment on column specimens.id is 'Unique identifier of a specimen or batch of specimens';
comment on column specimens.collection_ref is 'Reference of collection the specimen is grouped under - id field of collections table';
comment on column specimens.expedition_ref is 'When acquisition category is expedition, contains the reference of the expedition having conducted to the current specimen capture - id field of expeditions table';
comment on column specimens.gtu_ref is 'Reference of the sampling location the specimen is coming from - id field of gtu table';
comment on column specimens.litho_ref is 'When encoding a rock, mineral or paleontologic specimen, contains the reference of lithostratigraphic unit the specimen have been found into - id field of lithostratigraphy table';
comment on column specimens.chrono_ref is 'When encoding a rock, mineral or paleontologic specimen, contains the reference of chronostratigraphic unit the specimen have been found into - id field of chronostratigraphy table';
comment on column specimens.taxon_ref is 'When encoding a ''living'' specimen, contains the reference of the taxon unit defining the specimen - id field of taxa table';
comment on column specimens.identification_qual is 'Qualifier of taxonomic definition: sp., prox. aff., cf., ...';
comment on column specimens.identification_taxon_ref is 'When taxonomic qualifier specified - can contain the reference of the taxon the qualifier targetsi - id field of taxa table';
comment on column specimens.host_relationship is 'When current specimen encoded is in a host relationship with an other specimen or taxon, this field contains the type of relationship between them: symbiosis, parasitism, saprophytism,...';
comment on column specimens.host_specimen_ref is 'When current specimen encoded is in a host relationship with an other specimen, this field contains reference of the host specimen - recursive reference';
comment on column specimens.acquisition_category is 'Describe how the specimen was collected: expedition, donation,...';
comment on column specimens.acquisition_date_day is 'Day of specimen acquisition';
comment on column specimens.acquisition_date_month is 'Month of specimen acquisition';
comment on column specimens.acquisition_date_year is 'Year of specimen acquisition';
comment on column specimens.collecting_method is 'Collecting method used to collect the specimen';
comment on column specimens.collecting_tool is 'Collecting tool used to collect the specimen';
comment on column specimens.specimen_count_min is 'Minimum number of individuals in batch';
comment on column specimens.specimen_count_max is 'Maximum number of individuals in batch';
comment on column specimens.multimedia_visible is 'Flag telling if the multimedia attached to this specimen can be visible or not';
comment on column specimens.station_visible is 'Flag telling if the sampling location can be visible or must be hidden for the specimen encoded';
comment on column specimens.category is 'Type of specimen encoded: a physical object stored in collections, an observation, a figurate specimen,...';
create table template_codes
       (
        code_category code_categories default 'main' not null,
        code_prefix varchar,
        code integer,
        code_suffix varchar,
        full_code_indexed varchar not null,
        code_date timestamp
       );
comment on table template_codes is 'Template used to construct the specimen codes tables';
comment on column template_codes.code_category is 'Category of code: main, secondary, temporary,...';
comment on column template_codes.code_prefix is 'Code prefix - entire code if all alpha, begining character part if code is made of characters and numeric parts';
comment on column template_codes.code is 'Numerical part of code';
comment on column template_codes.code_suffix is 'For codes made of characters and numerical parts, this field stores the last alpha part of code';
comment on column template_codes.full_code_indexed is 'Full code composition by code_prefix, code and code suffix concatenation and indexed for unique check purpose';
comment on column template_codes.code_date is 'Date of code creation';
create table specimens_codes
       (
        specimen_ref integer not null,
        constraint unq_specimens_codes unique (specimen_ref, code_category, full_code_indexed),
        constraint fk_specimens_codes_specimens foreign key (specimen_ref) references specimens(id) on delete cascade
       )
inherits (template_codes);
comment on table specimens_codes is 'List of codes associated to a specimen';
comment on column specimens_codes.specimen_ref is 'Reference of specimen concerned - id field of specimens table';
comment on column specimens_codes.code_category is 'Category of code: main, secondary, temporary,...';
comment on column specimens_codes.code_prefix is 'Code prefix - entire code if all alpha, begining character part if code is made of characters and numeric parts';
comment on column specimens_codes.code is 'Numerical part of code';
comment on column specimens_codes.code_suffix is 'For codes made of characters and numerical parts, this field stores the last alpha part of code';
comment on column specimens_codes.full_code_indexed is 'Full code composition by code_prefix, code and code suffix concatenation and indexed for unique check purpose';
comment on column specimens_codes.code_date is 'Date of code creation';
create table multimedia_codes
       (
        multimedia_ref integer not null,
        constraint unq_multimedia_codes unique (multimedia_ref, code_category, full_code_indexed),
        constraint fk_multimedia_codes_multimedia foreign key (multimedia_ref) references multimedia(id) on delete cascade
       )
inherits (template_codes);
comment on table multimedia_codes is 'List of codes associated to a specimen';
comment on column multimedia_codes.multimedia_ref is 'Reference of a multimedia object concerned - id field of multimedia table';
comment on column multimedia_codes.code_category is 'Category of code: main, secondary, temporary,...';
comment on column multimedia_codes.code_prefix is 'Code prefix - entire code if all alpha, begining character part if code is made of characters and numeric parts';
comment on column multimedia_codes.code is 'Numerical part of code';
comment on column multimedia_codes.code_suffix is 'For codes made of characters and numerical parts, this field stores the last alpha part of code';
comment on column multimedia_codes.full_code_indexed is 'Full code composition by code_prefix, code and code suffix concatenation and indexed for unique check purpose';
comment on column multimedia_codes.code_date is 'Date of code creation';
create table specimen_individuals
       (
        id serial not null,
        specimen_ref integer not null,
        type types_list default 'specimen' not null,
        type_group types_group_list,
        type_search types_search_list,
        sex sexes default 'undefined' not null,
        stage stages default 'undefined' not null,
        state specimens_states default 'not applicable' not null,
        social_status socials_status default 'not applicable' not null,
        rock_form rock_forms default 'not applicable' not null,
        specimen_individuals_count_min integer default 1 not null,
        specimen_individuals_count_max integer default 1 not null,
        constraint pk_specimen_individuals primary key (id),
        constraint unq_specimen_individuals unique (specimen_ref, type, sex, stage, state, social_status, rock_form),
        constraint fk_specimen_individuals_specimens foreign key (specimen_ref) references specimens(id) on delete cascade,
        constraint chk_chk_specimen_individuals_minmax check (specimen_individuals_count_min <= specimen_individuals_count_max)
       );
comment on table specimen_individuals is 'Stores characterized individudals from a specimen batch';
comment on column specimen_individuals.id is 'Unique identifier of a specimen individual';
comment on column specimen_individuals.specimen_ref is 'Reference of a specimen batch the individual(s) is/are extracted from';
comment on column specimen_individuals.type is 'Special status given to individual(s): holotype, paratype,...';
comment on column specimen_individuals.type_group is 'For some special status, a common appelation is used - ie: topotype and cotype are joined into a common appelation of syntype';
comment on column specimen_individuals.type_search is 'On the interface, the separation in all special status is not suggested for non official appelations. For instance, an unified grouping name is provided: type for non official appelation,...';
comment on column specimen_individuals.sex is 'Individual sex: male , female,...';
comment on column specimen_individuals.stage is 'Individual stage: adult, juvenile,...';
comment on column specimen_individuals.state is 'Individual state - a sex complement: ovigerous, pregnant,...';
comment on column specimen_individuals.social_status is 'For social specimens, give the social status/role of individual in colony';
comment on column specimen_individuals.rock_form is 'For rock specimens/individuals, a descriptive form can be given: polygonous,...';
comment on column specimen_individuals.specimen_individuals_count_min is 'Minimum number of individuals';
comment on column specimen_individuals.specimen_individuals_count_max is 'Maximum number of individuals';
create table specimen_parts
       (
        id serial not null,
        specimen_individual_ref integer not null,
        specimen_part specimens_parts default 'specimen' not null,
        complete boolean default true not null,
        building varchar,
        floor varchar,
        room varchar,
        row varchar, 
        shelf varchar,
        container varchar,
        sub_container varchar,
        container_type container_types default 'container' not null,
        sub_container_type container_types default 'container' not null,
        storage storages default 'dry' not null,
        surnumerary boolean default false not null,
        specimen_status varchar default 'good state' not null,
        specimen_part_count_min integer default 1 not null,
        specimen_part_count_max integer default 1 not null,
        constraint pk_specimen_parts primary key (id),
        constraint fk_specimen_parts_specimen_individuals foreign key (specimen_individual_ref) references specimen_individuals(id) on delete cascade,
        constraint chk_chk_specimen_parts_minmax check (specimen_part_count_min <= specimen_part_count_max)
       );
comment on table specimen_parts is 'List of individuals or parts of individuals stored in conservatories';
comment on column specimen_parts.id is 'Unique identifier of a specimen part/individual';
comment on column specimen_parts.specimen_individual_ref is 'Reference of corresponding characterized specimen';
comment on column specimen_parts.specimen_part is 'Description of the part stored in conservatory: the whole specimen or a given precise part such as skelleton, head, fur,...';
comment on column specimen_parts.building is 'Building the part/individual is stored in';
comment on column specimen_parts.floor is 'Floor the part/individual is stored in';
comment on column specimen_parts.room is 'Room the part/individual is stored in';
comment on column specimen_parts.row is 'Row the part/individual is stored in';
comment on column specimen_parts.shelf is 'Shelf the part/individual is stored in';
comment on column specimen_parts.container is 'Container the part/individual is stored in';
comment on column specimen_parts.sub_container is 'Sub-Container the part/individual is stored in';
comment on column specimen_parts.container_type is 'Type of container: box, plateau-caisse,...';
comment on column specimen_parts.sub_container_type is 'Type of sub-container: slide, needle,...';
comment on column specimen_parts.storage is 'Conservative medium used: formol, alcohool, dry,...';
comment on column specimen_parts.surnumerary is 'Tells if this part/individual has been added after first inventory';
comment on column specimen_parts.specimen_status is 'Specimen status: good state, lost, damaged,...';
comment on column specimen_parts.specimen_part_count_min is 'Minimum number of parts/individuals';
comment on column specimen_parts.specimen_part_count_max is 'Maximum number of parts/individuals';
create table specimen_parts_codes
       (
        specimen_part_ref integer not null,
        constraint unq_specimen_parts_codes unique (specimen_part_ref, code_category, full_code_indexed),
        constraint fk_specimen_parts_codes_specimen_parts foreign key (specimen_part_ref) references specimen_parts(id) on delete cascade
       )
inherits (template_codes);
comment on table specimen_parts_codes is 'List of codes given to specimen parts/individuals';
comment on column specimen_parts_codes.specimen_part_ref is 'Reference of specimen part concerned - id field of specimen_parts table';
comment on column specimen_parts_codes.code_category is 'Category of code: main, secondary, temporary,...';
comment on column specimen_parts_codes.code_prefix is 'Code prefix - entire code if all alpha, begining character part if code is made of characters and numeric parts';
comment on column specimen_parts_codes.code is 'Numerical part of code';
comment on column specimen_parts_codes.code_suffix is 'For codes made of characters and numerical parts, this field stores the last alpha part of code';
comment on column specimen_parts_codes.full_code_indexed is 'Full code composition by code_prefix, code and code suffix concatenation and indexed for unique check purpose';
comment on column specimen_parts_codes.code_date is 'Date of code creation';
create table specimen_parts_insurances
       (
        specimen_part_ref integer not null,
        insurance_year smallint default extract(year from now()) not null,
        insurance_value numeric default 0 not null,
        insurer_ref integer,
        constraint unq_specimen_parts_insurances unique (specimen_part_ref, insurance_year),
        constraint fk_specimen_parts_insurances_specimen_parts foreign key (specimen_part_ref) references specimen_parts(id) on delete cascade,
        constraint fk_specimen_parts_insurances_people foreign key (insurer_ref) references people(id)
       );
comment on table specimen_parts_insurances is 'List of insurances values for given specimen parts/individuals';
comment on column specimen_parts_insurances.specimen_part_ref is 'Reference of specimen part/individual concerned - id field of specimen_parts table';
comment on column specimen_parts_insurances.insurance_year is 'Reference year for insurance subscription';
comment on column specimen_parts_insurances.insurance_value is 'Insurance value';
comment on column specimen_parts_insurances.insurer_ref is 'Reference of the insurance firm an insurance have been subscripted at';
create table associated_multimedia
       (
        table_ref integer not null,
        record_id integer not null,
        multimedia_ref integer not null,
        constraint unq_associated_multimedia unique (multimedia_ref, table_ref, record_id),
        constraint fk_associated_multimedia_multimedia foreign key (multimedia_ref) references multimedia(id) on delete cascade,
        constraint fk_associated_multimedia_table_list foreign key (table_ref) references table_list(id)
       );
comment on table associated_multimedia is 'List of all associated multimedia to an element of DaRWIN 2 application: specimen, catalogue unit';
comment on column associated_multimedia.table_ref is 'Reference of table concerned - id field of table_list table';
comment on column associated_multimedia.record_id is 'Identifier of record concerned';
comment on column associated_multimedia.multimedia_ref is 'Reference of multimedia object concerned - id field of multimedia table';
create table specimens_accompanying
       (
        type accompanying_types default 'secondary' not null,
        specimen_ref integer not null,
        taxon_ref integer default 0 not null,
        mineral_ref integer default 0 not null,
        form varchar,
        quantity real,
        unit varchar default '%' not null,
        defined_by_ordered_ids_list integer[],
        constraint unq_specimens_accompanying unique (specimen_ref, taxon_ref, mineral_ref),
        constraint fk_specimens_accompanying_specimens foreign key (specimen_ref) references specimens(id) on delete cascade,
        constraint fk_specimens_accompanying_mineralogy foreign key (mineral_ref) references mineralogy(id),
        constraint fk_specimens_accompanying_taxa foreign key (taxon_ref) references taxa(id)
       );
comment on table specimens_accompanying is 'For rock or minerals specimens, will list all the accompanying minerals found';
comment on column specimens_accompanying.specimen_ref is 'Reference of specimen concerned - id field of specimens table';
comment on column specimens_accompanying.mineral_ref is 'Reference of accompanying mineral - id field of mineralogy table';
comment on column specimens_accompanying.type is 'Type of mineral: main or secondary';
comment on column specimens_accompanying.quantity is 'Quantity of mineral';
comment on column specimens_accompanying.unit is 'Unit used for quantity of mineral presence';
comment on column specimens_accompanying.defined_by_ordered_ids_list is 'Array of persons ids having defined these accompanying minerals';
