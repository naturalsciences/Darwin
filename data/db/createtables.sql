create table :dbname.template_tablefields_common
       (
        id serial not null,
        name varchar not null
       );
comment on table :dbname.template_tablefields_common is 'Template table used to define common fields for table_list and field_list tables';
comment on column :dbname.template_tablefields_common.id is 'Unique auto-incremented identifier';
comment on column :dbname.template_tablefields_common.name is 'Name';
create table :dbname.table_list 
       (
        constraint table_list_pk primary key (id),
        constraint table_list_name_unq unique (name)
       )
inherits (:dbname.template_tablefields_common);
comment on table :dbname.table_list is 'List of darwin 2 database tables';
comment on column :dbname.table_list.id is 'Unique auto-incremented identifier of a darwin 2 database table';
comment on column :dbname.table_list.name is 'Table name';
create table :dbname.field_list
       (
        table_ref integer not null,
        constraint field_list_pk primary key (id),
        constraint field_list_name_unq unique (name, table_ref),
        constraint field_list_table_list_fk foreign key (table_ref) references :dbname.table_list(id)
       )
inherits (:dbname.template_tablefields_common);
comment on table :dbname.field_list is 'List of fields for each darwin 2 db tables';
comment on column :dbname.field_list.id is 'Unique identifier of a field';
comment on column :dbname.field_list.name is 'Field name';
comment on column :dbname.field_list.table_ref is 'Reference to a table definition - id field of table_list table';
create table :dbname.catalogue_relationships
       (
        table_ref integer not null,
        record_id_1 integer not null,
        record_id_2 integer not null,
        relationship_type varchar default 'parent of' not null,
        defined_by_ordered_ids_list integer[],
        constraint catalogue_relationships_unq unique (table_ref, relationship_type, record_id_1, record_id_2),
        constraint catalogue_rel_table_list_fk foreign key (table_ref) references :dbname.table_list(id)
       );
comment on table :dbname.catalogue_relationships is 'Stores the relationships between records of a table - synonymy, parenty, current name, original combination, ...';
comment on column :dbname.catalogue_relationships.table_ref is 'Reference of the table a relationship is defined for - id field of table_list table';
comment on column :dbname.catalogue_relationships.record_id_1 is 'Identifier of record in relation with an other one (record_id_2)';
comment on column :dbname.catalogue_relationships.record_id_2 is 'Identifier of record in relation with an other one (record_id_1)';
comment on column :dbname.catalogue_relationships.relationship_type is 'Type of relation between record 1 and record 2 - synonymy, parenty, current name, original combination, ...';
comment on column :dbname.catalogue_relationships.defined_by_ordered_ids_list is 'Array of persons identifiers (id fields of people table) having defined this relationship';
create table :dbname.template_table_record_ref
       (
        table_ref integer not null,
        record_id integer not null
       );
create table :dbname.catalogue_authors
       (
        author_type varchar default 'main author' not null,
        authors_ordered_ids_list integer[] not null,
        defined_by_ordred_ids_list integer[],
        constraint catalogue_authors_unq unique (table_ref, author_type, record_id),
        constraint catalogue_authors_table_list_fk foreign key (table_ref) references :dbname.table_list(id)
       )
inherits (:dbname.template_table_record_ref);
comment on table :dbname.catalogue_authors is 'List of authors of catalogues units - Taxonomy, Chronostratigraphy,...';
comment on column :dbname.catalogue_authors.table_ref is 'Identifier of table the units come from - id field of table_list table';
comment on column :dbname.catalogue_authors.record_id is 'Identifier of record concerned in table concerned';
comment on column :dbname.catalogue_authors.author_type is 'Type of "author" associated to the catalogue unit: Main author, Secondary author, Expert, Corrector,... - default value is main author';
comment on column :dbname.catalogue_authors.authors_ordered_ids_list is 'Array of "authors" identifiers - List of authors associated to the unit concerned - Identifiers are id fields from people table';
comment on column :dbname.catalogue_authors.defined_by_ordred_ids_list is 'Array of persons having defined this catalogue authors entry - id fields from people table';
create table :dbname.catalogue_levels
       (
        id serial not null,
        level_type varchar not null,
        level_name varchar not null,
        optional_level boolean default false not null,
        constraint catalogue_levels_pk primary key (id),
        constraint catalogue_levels_unq unique (level_type, level_name),
        constraint catalogue_levels_level_type_chk check (level_type in ('Taxonomy', 'Chronostratigraphy', 'Lithostratigraphy', 'Lithology', 'Mineralogy'))
       );
comment on table :dbname.catalogue_levels is 'List of hierarchical units levels - organized by type of unit: taxonomy, chroostratigraphy,...';
comment on column :dbname.catalogue_levels.id is 'Unique identifier of a hierarchical unit level';
comment on column :dbname.catalogue_levels.level_type is 'Type of unit the levels is applicable to - contained in a predifined list: taxonomy, chronostratigraphy,...';
comment on column :dbname.catalogue_levels.level_name is 'Name given to level concerned';
comment on column :dbname.catalogue_levels.optional_level is 'Tells if the level is optional';
create table :dbname.levels_translations
       (
        level_id integer not null,
        study_domain varchar default 'zoology' not null,
        level_name varchar not null,
        constraint levels_translations_unq unique (level_id, study_domain),
        constraint levels_translations_catalogue_levels_fk foreign key (level_id) references :dbname.catalogue_levels(id),
        constraint levels_translations_study_domain_chk check(study_domain in ('zoology', 'botany', 'bacteriology', 'mineralogy', 'lithology', 'chronostratigraphy', 'lithostratigraphy'))
       );
comment on table :dbname.levels_translations is 'Translations of levels - Only the levels translated for the domain concerned are usable in the interface - works as a filter';
comment on column :dbname.levels_translations.level_id is 'Reference of the level concerned - comes from id field of catalogue_levels table';
comment on column :dbname.levels_translations.study_domain is 'Study domain: Zoology, Botany, Lithology,... - used to filter the available levels in the interface. ie.: In Botany some levels are used that are not available in Zoology';
comment on column :dbname.levels_translations.level_name is 'Level name in english';
create table :dbname.possible_upper_levels
       (
        level_ref integer not null,
        level_upper_ref integer not null,
        constraint possible_upper_levels_unq unique (level_ref, level_upper_ref),
        constraint possible_upper_levels_catalogue_levels_01_fk foreign key (level_ref) references :dbname.catalogue_levels(id),
        constraint possible_upper_levels_catalogue_levels_02_fk foreign key (level_upper_ref) references :dbname.catalogue_levels(id)
       );
comment on table :dbname.possible_upper_levels is 'For each level, list all the availble parent levels';
comment on column :dbname.possible_upper_levels.level_ref is 'Reference of current level';
comment on column :dbname.possible_upper_levels.level_upper_ref is 'Reference of authorized parent level';
create table :dbname.comments
       (
        table_ref integer not null,
        field_ref integer,
        field_ref_unified integer not null,
        record_id integer not null,
        notion_concerned varchar not null,
        comment text not null,
        comment_ts tsvector not null,
        comment_language_full_text full_text_language, 
        constraint comments_unq unique (table_ref, field_ref_unified, record_id, notion_concerned),
        constraint comments_table_list_fk foreign key (table_ref) references :dbname.table_list(id),
        constraint comments_field_list_fk foreign key (field_ref) references :dbname.field_list(id)
       );
comment on table :dbname.comments is 'Comments associated to a record of a given table (and maybe a given field) on a given subject';
comment on column :dbname.comments.table_ref is 'Reference of table a comment is posted for - id field of table_list table';
comment on column :dbname.comments.field_ref is 'Reference of field a comment is posted for - id field of field_list table';
comment on column :dbname.comments.field_ref_unified is 'Used for unique indexation - If no field_ref has been provided than field_ref_unified takes a dummy value, otherwise takes value from field_ref field';
comment on column :dbname.comments.record_id is 'Identifier of the record concerned';
comment on column :dbname.comments.notion_concerned is 'Notion concerned by comment';
comment on column :dbname.comments.comment is 'Comment';
comment on column :dbname.comments.comment_ts is 'tsvector form of comment field';
comment on column :dbname.comments.comment_language_full_text is 'Corresponding language to the language/country reference recognized by full text search to_tsvector function';
create table :dbname.tags
       (
        id serial not null,
        label varchar not null,
        label_indexed varchar not null,
        constraint tags_pk primary key (id),
        constraint tags_label_unq unique (label_indexed)
       );
comment on table :dbname.tags is 'List of all tags introduced to describe GTUs';
comment on column :dbname.tags.id is 'Unique identifier of a tag';
comment on column :dbname.tags.label is 'Tag';
comment on column :dbname.tags.label_indexed is 'Indexed form of tag';
create table :dbname.tag_groups
       (
        id bigserial not null,
        tag_ref integer not null,
        group_name varchar not null,
        group_name_indexed varchar not null,
        color varchar default '#FFFFFF' not null,
        constraint tag_groups_pk primary key (id),
        constraint tag_groups_tags_fk foreign key (tag_ref) references :dbname.tags(id),
        constraint tag_groups_unq unique (tag_ref, group_name_indexed)
       );
comment on table :dbname.tag_groups is 'List of grouped tags';
comment on column :dbname.tag_groups.id is 'Unique identifier of a grouped tag';
comment on column :dbname.tag_groups.tag_ref is 'Reference of tag - id field from tags table';
comment on column :dbname.tag_groups.group_name is 'Group name under which the tag is grouped: Country, River, Mountain,...';
comment on column :dbname.tag_groups.group_name_indexed is 'Indexed form of a group name';
comment on column :dbname.tag_groups.color is 'Color associated to the group concerned';
create table :dbname.gtu
       (
        id serial not null,
        code varchar not null,
        parent_ref integer not null,
        date_from timestamp,
        date_to timestamp,
        constraint gtu_pk primary key (id),
        constraint gtu_gtu_fk foreign key (parent_ref) references :dbname.gtu(id)
       );
comment on table :dbname.gtu is 'Location or sampling units - GeoTemporalUnits';
comment on column :dbname.gtu.id is 'Unique identifier of a location or sampling unit';
comment on column :dbname.gtu.code is 'Code given - for sampling units - takes id if none defined';
comment on column :dbname.gtu.parent_ref is 'Recursive reference to a parent location-sampling unit - id field of gtu table itself';
comment on column :dbname.gtu.date_from is 'In temporal scale, start of location or sampling unit existence';
comment on column :dbname.gtu.date_to is 'In temporal scale, stop of location or sampling unit existence';
create table :dbname.catalogue_properties
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
        constraint catalogue_properties_unq unique (table_ref, record_id, property_type, property_sub_type_indexed, date_from_indexed, date_to_indexed, property_method_indexed, property_tool_indexed),
        constraint catalogue_properties_table_list_fk foreign key (table_ref) references :dbname.table_list(id)
       )
inherits (:dbname.template_table_record_ref);
comment on table :dbname.catalogue_properties is 'All properties or all measurements describing an object in darwin are stored in this table';
comment on column :dbname.catalogue_properties.table_ref is 'Identifier of the table a property is defined for - id field of table_list table';
comment on column :dbname.catalogue_properties.record_id is 'Identifier of record a property is defined for';
comment on column :dbname.catalogue_properties.property_type is 'Type-Category of property - Latitude, Longitude, Ph, Height, Weight, Color, Temperature, Wind direction,...';
comment on column :dbname.catalogue_properties.property_sub_type is 'Sub type or sub category of property: For Latitudes and Longitudes, precise which type of lat/long it is like Lambert 72, Lambert 92, UTM,...';
comment on column :dbname.catalogue_properties.property_sub_type_indexed is 'Indexed form of Sub type of property - if subtype is null, takes a generic replacement value';
comment on column :dbname.catalogue_properties.date_from is 'For a range of measurements, give the measurement start';
comment on column :dbname.catalogue_properties.date_from_indexed is 'Indexed form of date_from field - if null, takes a generic replacement value';
comment on column :dbname.catalogue_properties.date_to is 'For a range of measurements, give the measurement stop date/time';
comment on column :dbname.catalogue_properties.date_to_indexed is 'Indexed form of date_to field - if null, takes a generic replacement value';
comment on column :dbname.catalogue_properties.property_unit is 'Unit used for property value introduced';
comment on column :dbname.catalogue_properties.property_min is 'Array of one or more value(s) for the property type and subtype selected - in case of range of values store the minimum value or the mean minimum value - in case of range of all values, stores the whole range';
comment on column :dbname.catalogue_properties.property_min_unified is 'Unified version of property_min value(s) -> means that the value(s) is/are converted into a common unit allowing comparisons';
comment on column :dbname.catalogue_properties.property_max is 'Array of one or more value(s) for the property type and subtype selected - in case of range of values store the maximum value or the mean maximum value - in case of range of all values, stores nothing';
comment on column :dbname.catalogue_properties.property_max_unified is 'Unified version of property_max value(s) -> means that the value(s) is/are converted into a common unit al
lowing comparisons';
comment on column :dbname.catalogue_properties.property_accuracy_unit is 'Unit used for accuracy value(s)';
comment on column :dbname.catalogue_properties.property_accuracy is 'Accuracy of property measurement';
comment on column :dbname.catalogue_properties.property_accuracy_unified is 'Unified version of accuracy on property or sub property value -> means that the value(s) is/are converted into a common unit allowing comparisons';
comment on column :dbname.catalogue_properties.property_method is 'Method used to collect property value';
comment on column :dbname.catalogue_properties.property_method_indexed is 'Indexed version of property_method field - if null, takes a generic replacement value';
comment on column :dbname.catalogue_properties.property_tool is 'Tool used to collect property value';
comment on column :dbname.catalogue_properties.property_tool_indexed is 'Indexed version of property_tool field - if null, takes a generic replacement value';
comment on column :dbname.catalogue_properties.defined_by_ordered_ids_list is 'Array of identifiers of persons having defined this property - array of id field from people table';
create table :dbname.identifications_expertises
       (
        notion_concerned varchar not null,
        notion_date timestamp,
        notion_date_indexed timestamp not null,
        persons_ordered_ids_list integer[] not null,
        value_defined varchar,
        value_defined_ts tsvector,
        value_defined_indexed varchar not null,
        determination_status varchar,
        comment_diagnostic text,
        comment_diagnostic_ts tsvector,
        language_full_text full_text_language,
        defined_by_ordered_ids_list integer[],
        constraint identifications_expertises_unq unique (table_ref, record_id, notion_concerned, notion_date_indexed, value_defined_indexed),
        constraint identifications_expertises_table_list_fk foreign key (table_ref) references :dbname.table_list(id),
        constraint identifications_expertises_notion_chk check (notion_concerned in ('Taxonomic identification', 'Stratigraphic identification', 'Lithologic identification', 'Mineralogic identification', 'Expertise', 'Preparation'))
       )
inherits (:dbname.template_table_record_ref);
comment on table :dbname.identifications_expertises is 'History of identifications and expertises';
comment on column :dbname.identifications_expertises.table_ref is 'Reference of table an identification/expertise is introduced for';
comment on column :dbname.identifications_expertises.record_id is 'Id of record concerned by an identification/expertise entry';
comment on column :dbname.identifications_expertises.notion_concerned is 'Type of entry: Identification, expertise or preparation';
comment on column :dbname.identifications_expertises.notion_date is 'Date of identification/expertise or preparation';
comment on column :dbname.identifications_expertises.notion_date_indexed is 'Indexed form of identification/expertise/preparation date - if null, takes a generic replacement value';
comment on column :dbname.identifications_expertises.persons_ordered_ids_list is 'Array of who made the identifications/expertises/preparations - array of id field from people table';
comment on column :dbname.identifications_expertises.value_defined is 'When making identification, stores the value resulting of this identification';
comment on column :dbname.identifications_expertises.value_defined_ts is 'tsvector form of value_defined field';
comment on column :dbname.identifications_expertises.value_defined_indexed is 'Indexed form of value_defined field';
comment on column :dbname.identifications_expertises.determination_status is 'Status of identification - can either be a percentage of certainty or a code describing the identification step in the process';
comment on column :dbname.identifications_expertises.comment_diagnostic is 'Complementary comments or the diagnostic that conducted to this identification';
comment on column :dbname.identifications_expertises.comment_diagnostic_ts is 'tsvector version of comment_diagnostic field';
comment on column :dbname.identifications_expertises.language_full_text is 'Associated language to language/country definition, used by full text search to_tsvector function';
comment on column :dbname.identifications_expertises.defined_by_ordered_ids_list is 'Array of persons who have defined this entry - array of id fields from people table';
create table :dbname.class_vernacular_names
       (
        id serial not null,
        community varchar not null,
        defined_by_ordered_ids_list integer[],
        constraint class_vernacular_names_pk primary key (id),
        constraint class_vernacular_names_unq unique (table_ref, record_id, community),
        constraint class_vernacular_names_table_list_fk foreign key (table_ref) references :dbname.table_list(id)
       )
inherits (:dbname.template_table_record_ref);
comment on table :dbname.class_vernacular_names is 'Contains the language communities a unit name translation is available for';
comment on column :dbname.class_vernacular_names.id is 'Unique identifier of a language community vernacular name';
comment on column :dbname.class_vernacular_names.table_ref is 'Reference of the unit table a vernacular name for a language community has to be defined - id field of table_list table';
comment on column :dbname.class_vernacular_names.record_id is 'Identifier of record a vernacular name for a language community has to be defined';
comment on column :dbname.class_vernacular_names.community is 'Language community, a unit translation is available for';
comment on column :dbname.class_vernacular_names.defined_by_ordered_ids_list is 'Array of persons ids having defined this entry';
create table :dbname.vernacular_names
       (
        vernacular_class_ref integer not null,
        name varchar not null,
        name_ts tsvector not null,
        name_indexed varchar not null,
        language_country_full_text full_text_language,
        constraint vernacular_names_unq unique (vernacular_class_ref, name_indexed),
        constraint vernacular_class_class_vernacular_names_fk foreign key (vernacular_class_ref) references :dbname.class_vernacular_names(id)
       );
comment on table :dbname.vernacular_names is 'List of vernacular names for a given unit and a given language community';
comment on column :dbname.vernacular_names.vernacular_class_ref is 'Identifier of a unit/language community entry - id field of class_vernacular_names table';
comment on column :dbname.vernacular_names.name is 'Vernacular name';
comment on column :dbname.vernacular_names.name_ts is 'tsvector version of name field';
comment on column :dbname.vernacular_names.name_indexed is 'Indexed form of vernacular name';
comment on column :dbname.vernacular_names.language_country_full_text is 'Language used by full text search to_tsvector function';
create table :dbname.expeditions
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
        constraint expeditions_pk primary key (id)
       );
comment on table :dbname.expeditions is 'List of expeditions made to collect specimens';
comment on column :dbname.expeditions.id is 'Unique identifier of an expedition';
comment on column :dbname.expeditions.name is 'Expedition name';
comment on column :dbname.expeditions.name_ts is 'tsvector version of name field';
comment on column :dbname.expeditions.name_indexed is 'Indexed form of expedition name';
comment on column :dbname.expeditions.name_language_full_text is 'Language associated to language/country reference used by full text search to_tsvector function';
comment on column :dbname.expeditions.expedition_from_date_day is 'Start day';
comment on column :dbname.expeditions.expedition_from_date_month is 'Start month';
comment on column :dbname.expeditions.expedition_from_date_year is 'Start year';
comment on column :dbname.expeditions.expedition_to_date_day is 'End day';
comment on column :dbname.expeditions.expedition_to_date_month is 'End month';
comment on column :dbname.expeditions.expedition_to_date_year is 'End year';
comment on column :dbname.expeditions.expedition_from_date is 'When all three from date fields are filled, this field contains the full date composition - will help for dates comparisons';
comment on column :dbname.expeditions.expedition_to_date is 'When all three to date fields are filled, this field contains the full date composition - will help for dates comparisons';
create table :dbname.template_people
       (
        id serial not null,
        type boolean default true not null,
        sub_type varchar,
        public_class varchar default 'public' not null,
        formated_name varchar not null,
        formated_name_ts tsvector not null,
        formated_name_indexed varchar not null,
        formated_name_language_full_text full_text_language,
        family_name varchar not null,
        family_name_indexed varchar not null,
        given_name varchar,
        given_name_indexed varchar not null,
        additional_names varchar,
        birth_date_day date_day,
        birth_date_day_indexed date_day not null,
        birth_date_month date_month,
        birth_date_month_indexed date_month not null,
        birth_date_year date_year,
        birth_date_year_indexed date_year not null,
        birth_date date,
        gender genders,
        sort_string varchar(36) not null,
        note text,
        note_ts tsvector,
        note_language_full_text full_text_language,
        constraint template_people_public_class_chk check (public_class in ('public', 'private'))
       );
comment on table :dbname.template_people is 'Template table used to describe user/people tables';
comment on column :dbname.template_people.id is 'Unique identifier of a user/person';
comment on column :dbname.template_people.type is 'Type of user/person: physical or moral - true is physical, false is moral';
comment on column :dbname.template_people.sub_type is 'Used for moral user/persons: precise nature - public institution, asbl, sprl, sa,...';
comment on column :dbname.template_people.public_class is 'Tells public nature of user/person information - public is default value';
comment on column :dbname.template_people.formated_name is 'Complete user/person formated name (with honorific mention, prefixes, suffixes,...) - By default composed with family_name and given_name fields, but can be modified by hand';
comment on column :dbname.template_people.formated_name_ts is 'tsvector form of formated_name field';
comment on column :dbname.template_people.formated_name_indexed is 'Indexed form of formated_name field';
comment on column :dbname.template_people.formated_name_language_full_text is 'Name formated to fit a full text search with a to_tsvector indexation';
comment on column :dbname.template_people.family_name is 'Family name for physical user/persons and Organisation name for moral user/persons';
comment on column :dbname.template_people.family_name_indexed is 'Indexed form of family_name field';
comment on column :dbname.template_people.given_name is 'User/person''s given name - usually first name';
comment on column :dbname.template_people.given_name_indexed is 'Indexed form of given_name field';
comment on column :dbname.template_people.additional_names is 'Any additional names given to user/person';
comment on column :dbname.template_people.birth_date_day is 'Day of birth/creation';
comment on column :dbname.template_people.birth_date_day_indexed is 'Indexed form of birth_date_day field';
comment on column :dbname.template_people.birth_date_month is 'Month of birth/creation';
comment on column :dbname.template_people.birth_date_month_indexed is 'Indexed form of birth_date_month field';
comment on column :dbname.template_people.birth_date_year is 'Year of birth/creation';
comment on column :dbname.template_people.birth_date_year_indexed is 'Indexed form of birth_date_year field';
comment on column :dbname.template_people.birth_date is 'Birth/Creation date composed from the three birth/creation date fields: birth_date_day, birth_date_month, birth_date_year';
comment on column :dbname.template_people.gender is 'For physical user/persons give the gender: M or F';
comment on column :dbname.template_people.sort_string is 'String used for sorting - composed from family_name_indexed and given_name_indexed fields';
comment on column :dbname.template_people.note is 'General added notes';
comment on column :dbname.template_people.note_ts is 'tsvector form of note field';
comment on column :dbname.template_people.note_language_full_text is 'Language used by full text to_tsvector function';
create table :dbname.template_people_languages
       (
        language_country varchar default 'eng_GB' not null,
        mother boolean default true not null
       );
comment on table :dbname.template_people_languages is 'Template supporting users/people languages table definition';
comment on column :dbname.template_people_languages.language_country is 'Reference of Language - language_country field of languages_countries table';
comment on column :dbname.template_people_languages.mother is 'Flag telling if its mother language or not';
create table :dbname.people
       (
        db_people_type integer default 1 not null,
        end_date_day date_day,
        end_date_day_indexed date_day not null,
        end_date_month date_month,
        end_date_month_indexed date_month not null,
        end_date_year date_year,
        end_date_year_indexed date_year not null,
        end_date date,
        constraint people_pk primary key (id),
        constraint people_unq unique (type, formated_name_indexed, birth_date_day_indexed, birth_date_month_indexed, birth_date_year_indexed, end_date_day_indexed, end_date_month_indexed, end_date_year_indexed)
       )
inherits (:dbname.template_people);
comment on table :dbname.people is 'All physical and moral persons used in the application are here stored';
comment on column :dbname.people.id is 'Unique identifier of a person';
comment on column :dbname.people.type is 'Type of person: physical or moral - true is physical, false is moral';
comment on column :dbname.people.sub_type is 'Used for moral persons: precise nature - public institution, asbl, sprl, sa,...';
comment on column :dbname.people.public_class is 'Tells public nature of person information - public is default value';
comment on column :dbname.people.formated_name is 'Complete person formated name (with honorific mention, prefixes, suffixes,...) - By default composed with family_name and given_name fields, but can be modified by hand';
comment on column :dbname.people.formated_name_ts is 'tsvector form of formated_name field';
comment on column :dbname.people.formated_name_indexed is 'Indexed form of formated_name field';
comment on column :dbname.people.formated_name_language_full_text is 'Name formated to fit a full text search with a to_tsvector indexation';
comment on column :dbname.people.family_name is 'Family name for physical persons and Organisation name for moral persons';
comment on column :dbname.people.family_name_indexed is 'Indexed form of family_name field';
comment on column :dbname.people.given_name is 'User/person''s given name - usually first name';
comment on column :dbname.people.given_name_indexed is 'Indexed form of given_name field';
comment on column :dbname.people.additional_names is 'Any additional names given to person';
comment on column :dbname.people.birth_date_day is 'Day of birth/creation';
comment on column :dbname.people.birth_date_day_indexed is 'Indexed form of birth_date_day field';
comment on column :dbname.people.birth_date_month is 'Month of birth/creation';
comment on column :dbname.people.birth_date_month_indexed is 'Indexed form of birth_date_month field';
comment on column :dbname.people.birth_date_year is 'Year of birth/creation';
comment on column :dbname.people.birth_date_year_indexed is 'Indexed form of birth_date_year field';
comment on column :dbname.people.birth_date is 'Birth/Creation date composed from the three birth/creation date fields: birth_date_day, birth_date_month, birth_date_year';
comment on column :dbname.people.gender is 'For physical persons give the gender: M or F';
comment on column :dbname.people.sort_string is 'String used for sorting - composed from family_name_indexed and given_name_indexed fields';
comment on column :dbname.people.note is 'General added notes';
comment on column :dbname.people.note_ts is 'tsvector form of note field';
comment on column :dbname.people.db_people_type is 'Sum of numbers in an arithmetic suite (1,2,4,8,...) that gives a unique number identifying people roles - each roles represented by one of the number in the arithmetic suite: 1 is contact, 2 is author, 4 is identifier, 8 is expert, 16 is collector,...';
comment on column :dbname.people.end_date_day is 'End date day';
comment on column :dbname.people.end_date_day_indexed is 'Indexed form of end date day';
comment on column :dbname.people.end_date_month is 'End date month';
comment on column :dbname.people.end_date_month_indexed is 'Indexed form of end date month';
comment on column :dbname.people.end_date_year is 'End date year';
comment on column :dbname.people.end_date_year_indexed is 'Indexed form of end date year';
comment on column :dbname.people.end_date is 'End date composed from the three end date fields: end_date_day, end_date_month, end_date_year';
comment on column :dbname.people.note_language_full_text is 'Language used by to_tsvector full text search function';
create table :dbname.users
       (
        constraint users_pk primary key (id),
        constraint users_unq unique (type, formated_name_indexed, birth_date_day_indexed, birth_date_month_indexed, birth_date_year_indexed)
       )
inherits (:dbname.template_people);
comment on table :dbname.users is 'List all application users';
comment on column :dbname.users.id is 'Unique identifier of a user';
comment on column :dbname.users.type is 'Type of user: physical or moral - true is physical, false is moral';
comment on column :dbname.users.sub_type is 'Used for moral users: precise nature - public institution, asbl, sprl, sa,...';
comment on column :dbname.users.public_class is 'Tells public nature of user information - public is default value';
comment on column :dbname.users.formated_name is 'Complete user formated name (with honorific mention, prefixes, suffixes,...) - By default composed with family_name and given_name fields, but can be modified by hand';
comment on column :dbname.users.formated_name_ts is 'tsvector form of formated_name field';
comment on column :dbname.users.formated_name_indexed is 'Indexed form of formated_name field';
comment on column :dbname.users.formated_name_language_full_text is 'Name formated to fit a full text search with a to_tsvector indexation';
comment on column :dbname.users.family_name is 'Family name for physical users and Organisation name for moral users';
comment on column :dbname.users.family_name_indexed is 'Indexed form of family_name field';
comment on column :dbname.users.given_name is 'User/user''s given name - usually first name';
comment on column :dbname.users.given_name_indexed is 'Indexed form of given_name field';
comment on column :dbname.users.additional_names is 'Any additional names given to user';
comment on column :dbname.users.birth_date_day is 'Day of birth/creation';
comment on column :dbname.users.birth_date_day_indexed is 'Indexed form of birth_date_day field';
comment on column :dbname.users.birth_date_month is 'Month of birth/creation';
comment on column :dbname.users.birth_date_month_indexed is 'Indexed form of birth_date_month field';
comment on column :dbname.users.birth_date_year is 'Year of birth/creation';
comment on column :dbname.users.birth_date_year_indexed is 'Indexed form of birth_date_year field';
comment on column :dbname.users.birth_date is 'Birth/Creation date composed from the three birth/creation date fields: birth_date_day, birth_date_month, birth_date_year';
comment on column :dbname.users.gender is 'For physical users give the gender: M or F';
comment on column :dbname.users.sort_string is 'String used for sorting - composed from family_name_indexed and given_name_indexed fields';
comment on column :dbname.users.note is 'General added notes';
comment on column :dbname.users.note_ts is 'tsvector form of note field';
comment on column :dbname.users.note_language_full_text is 'Language used by to_tsvector full text search function';
create table :dbname.people_languages
       (
        people_ref integer not null,
        constraint people_languages_unq unique (people_ref, language_country),
        constraint people_languages_people_fk foreign key (people_ref) references :dbname.people(id)
       )
inherits (:dbname.template_people_languages);
comment on table :dbname.people_languages is 'Languages spoken by a given person';
comment on column :dbname.people_languages.people_ref is 'Reference of person - id field of people table';
comment on column :dbname.people_languages.language_country is 'Reference of Language - language_country field of languages_countries table';
comment on column :dbname.people_languages.mother is 'Flag telling if its mother language or not';
create table :dbname.users_languages
       (
        user_ref integer not null,
        constraint users_languages_unq unique (user_ref, language_country),
        constraint users_languages_people_fk foreign key (user_ref) references :dbname.users(id)
       )
inherits (:dbname.template_people_languages);
comment on table :dbname.users_languages is 'Languages spoken by a given user';
comment on column :dbname.users_languages.user_ref is 'Reference of user - id field of users table';
comment on column :dbname.users_languages.language_country is 'Reference of Language - language_country field of languages_countries table';
comment on column :dbname.users_languages.mother is 'Flag telling if its mother language or not';
create table :dbname.multimedia
       (
        id serial not null,
        is_digital boolean default true not null,
        type varchar default 'image' not null,
        title varchar not null,
        title_ts tsvector not null,
        title_indexed varchar not null,
        title_full_text full_text_language,
        subject varchar default '/' not null,
        subject_ts tsvector not null,
        subject_indexed varchar default '/' not null,
        subject_full_text full_text_language,
        coverage varchar default 'temporal' not null,
        code varchar,
        code_indexed varchar not null,
        apercu_path varchar,
        copyright varchar,
        license varchar,
        uri varchar,
        creation_date date,
        publication_date date,
        constraint multimedia_pk primary key (id),
        constraint multimedia_unq unique (is_digital, type, title_indexed, code_indexed),
        constraint multimedia_coverage_chk check (coverage in ('temporal', 'spatial'))
       );
comment on table :dbname.multimedia is 'Stores all multimedia objects encoded in DaRWIN 2.0';
comment on column :dbname.multimedia.id is 'Unique identifier of a multimedia object';
comment on column :dbname.multimedia.is_digital is 'Flag telling if the object is digital (true) or physical (false)';
comment on column :dbname.multimedia.type is 'Main multimedia object type: image, sound, video,...';
comment on column :dbname.multimedia.title is 'Object title';
comment on column :dbname.multimedia.title_ts is 'tsvector version of title field';
comment on column :dbname.multimedia.title_indexed is 'Indexed form of title field';
comment on column :dbname.multimedia.title_full_text is 'title field transformed for a full text search with the to_tsvector function';
comment on column :dbname.multimedia.subject is 'Multimedia object subject (as required by Dublin Core...)';
comment on column :dbname.multimedia.subject_ts is 'tsvector version of subject field';
comment on column :dbname.multimedia.subject_indexed is 'Indexed form of subject field';
comment on column :dbname.multimedia.subject_full_text is 'subject field transformed for a full text search with the to_tsvector function';
comment on column :dbname.multimedia.coverage is 'Coverage of multimedia object: spatial or temporal (as required by Dublin Core...)';
comment on column :dbname.multimedia.code is 'Code given to a multimedia object';
comment on column :dbname.multimedia.code_indexed is 'Indexed form of code field';
comment on column :dbname.multimedia.apercu_path is 'URI path to the thumbnail illustrating the object';
comment on column :dbname.multimedia.copyright is 'Copyright notice';
comment on column :dbname.multimedia.license is 'License notice';
comment on column :dbname.multimedia.uri is 'URI of object if digital';
comment on column :dbname.multimedia.creation_date is 'Object creation date';
comment on column :dbname.multimedia.publication_date is 'Object publication date';
create table :dbname.template_people_users_comm_common
       (
        id serial not null,
        person_user_ref integer not null,
        comm_type varchar default 'address' not null,
        entry varchar not null,
        constraint template_people_comm_common_comm_type_chk check (comm_type in ('phone', 'e-mail', 'address'))
       );
comment on table :dbname.template_people_users_comm_common is 'Template table used to construct people communication tables (tel and e-mail)';
comment on column :dbname.template_people_users_comm_common.id is 'Unique identifier of a person/user communication entry';
comment on column :dbname.template_people_users_comm_common.person_user_ref is 'Reference of person/user - id field of people/users table';
comment on column :dbname.template_people_users_comm_common.comm_type is 'Type of communication table concerned: address, phone or e-mail';
comment on column :dbname.template_people_users_comm_common.entry is 'Communication entry';
create table :dbname.template_people_users_rel_common
       (
        organization_unit varchar,
        person_user_role varchar,
        activity_period varchar
       );
comment on table :dbname.template_people_users_rel_common is 'Template table used to propagate three field in different tables depending it''s people or user dedicated';
comment on column :dbname.template_people_users_rel_common.organization_unit is 'When a physical person/user is in relationship with a moral one, indicates the department or unit the person/user is related to';
comment on column :dbname.template_people_users_rel_common.person_user_role is 'Role the person/user have in the moral person he depends of';
comment on column :dbname.template_people_users_rel_common.activity_period is 'Person/User activity period';
create table :dbname.template_people_users_addr_common
       (
        po_box varchar,
        extended_address varchar,
        locality varchar not null,
        region varchar,
        zip_code varchar,
        country varchar not null
       );
comment on table :dbname.template_people_users_addr_common is 'Template table used to construct addresses tables for people/users';
comment on column :dbname.template_people_users_addr_common.po_box is 'PO Box';
comment on column :dbname.template_people_users_addr_common.extended_address is 'Address extension: State, Special post zip code characters,...';
comment on column :dbname.template_people_users_addr_common.locality is 'Locality';
comment on column :dbname.template_people_users_addr_common.region is 'Region';
comment on column :dbname.template_people_users_addr_common.zip_code is 'zip code';
comment on column :dbname.template_people_users_addr_common.country is 'Country';
create table :dbname.people_relationships
       (
        relationship_type varchar default 'belongs to' not null,
        person_1_ref integer not null,
        person_2_ref integer not null,
        person_title varchar,
        logo_ref integer,
        path varchar,
        constraint people_relationships_unq unique (relationship_type, person_1_ref, person_2_ref),
        constraint people_relationships_people_01_fk foreign key (person_1_ref) references :dbname.people(id),
        constraint people_relationships_people_02_fk foreign key (person_2_ref) references :dbname.people(id),
        constraint people_relationships_multimedia_fk foreign key (logo_ref) references :dbname.multimedia(id),
        constraint people_relationships_relationship_type_chk check (relationship_type in ('belongs to', 'is department of', 'is section of', 'works for'))
       )
inherits (:dbname.template_people_users_rel_common);
comment on table :dbname.people_relationships is 'Relationships between people - mainly between physical person and moral person: relationship of dependancy';
comment on column :dbname.people_relationships.relationship_type is 'Type of relationship between two persons: belongs to, is department of, is section of, works for,...';
comment on column :dbname.people_relationships.person_1_ref is 'Reference of person to be puted in relationship with an other - id field of people table';
comment on column :dbname.people_relationships.person_2_ref is 'Reference of person puted the person puted in relationship with is dependant of - id field of people table';
comment on column :dbname.people_relationships.organization_unit is 'When a physical person is in relationship with a moral one, indicates the department or unit the person is related to';
comment on column :dbname.people_relationships.person_title is 'Person title';
comment on column :dbname.people_relationships.person_user_role is 'Person role in the organization referenced';
comment on column :dbname.people_relationships.logo_ref is 'Reference of a multimedia object containing the logo of organization, department, section or role of a person in such structure';
comment on column :dbname.people_relationships.activity_period is 'Main person activity period or person activity period in the organization referenced';
comment on column :dbname.people_relationships.path is 'Hierarchical path of the organization structure';
create table :dbname.people_comm
       (
        constraint people_comm_pk primary key (id),
        constraint people_comm_unq unique (comm_type, person_user_ref, entry),
        constraint people_comm_people foreign key (person_user_ref) references :dbname.people(id)
       )
inherits (:dbname.template_people_users_comm_common);
comment on table :dbname.people_comm is 'People phones and e-mails';
comment on column :dbname.people_comm.id is 'Unique identifier of a person communication mean entry';
comment on column :dbname.people_comm.person_user_ref is 'Reference of person - id field of people table';
comment on column :dbname.people_comm.comm_type is 'Type of communication table concerned: phone or e-mail';
comment on column :dbname.people_comm.entry is 'Communication entry';
comment on column :dbname.people_comm.comm_type is 'Type of communication table concerned: address, phone or e-mail';
create table :dbname.people_addresses
       (
        address_parts_ts tsvector not null,
        constraint people_addresses_pk primary key (id),
        constraint people_addresses_unq unique (person_user_ref, entry, locality, country),
        constraint people_addresses_people_fk foreign key (person_user_ref) references :dbname.people(id)
       )
inherits (:dbname.template_people_users_comm_common, :dbname.template_people_users_addr_common);
comment on table :dbname.people_addresses is 'People addresses';
comment on column :dbname.people_addresses.address_parts_ts is 'tsvector column used to search an address part';
comment on column :dbname.people_addresses.id is 'Unique identifier of a person address';
comment on column :dbname.people_addresses.person_user_ref is 'Reference of the person concerned - id field of people table';
comment on column :dbname.people_addresses.po_box is 'PO Box';
comment on column :dbname.people_addresses.extended_address is 'Address extension: State, zip code suffix,...';
comment on column :dbname.people_addresses.entry is 'Street address';
comment on column :dbname.people_addresses.locality is 'Locality';
comment on column :dbname.people_addresses.country is 'Country';
comment on column :dbname.people_addresses.region is 'Region';
comment on column :dbname.people_addresses.zip_code is 'Zip code';
comment on column :dbname.people_addresses.comm_type is 'Type of communication table concerned: address, phone or e-mail';
create table :dbname.comm_addr_tags
       (
        tag varchar[] not null,
        constraint comm_addr_tags_unq unique (table_ref, record_id),
        constraint comm_addr_tags_table_list_fk foreign key (table_ref) references :dbname.table_list (id)
       )
inherits (:dbname.template_table_record_ref);
comment on table :dbname.comm_addr_tags is 'Tags associated to a communication entry of person/user';
comment on column :dbname.comm_addr_tags.table_ref is 'Reference of communication table concerned - id field of table_list table';
comment on column :dbname.comm_addr_tags.record_id is 'Record identifier of communication mean concerned';
comment on column :dbname.comm_addr_tags.tag is 'Array of tags associated to the communication entry to describe it';
create table :dbname.users_comm
       (
        constraint users_comm_pk primary key (id),
        constraint users_comm_unq unique (comm_type, person_user_ref, entry),
        constraint users_comm_users foreign key (person_user_ref) references :dbname.users(id)
       )
inherits (:dbname.template_people_users_comm_common);
comment on table :dbname.users_comm is 'Users phones and e-mails';
comment on column :dbname.users_comm.id is 'Unique identifier of a users communication mean entry';
comment on column :dbname.users_comm.person_user_ref is 'Reference of user - id field of user table';
comment on column :dbname.users_comm.comm_type is 'Type of communication table concerned: phone or e-mail';
comment on column :dbname.users_comm.entry is 'Communication entry';
comment on column :dbname.users_comm.comm_type is 'Type of communication table concerned: address, phone or e-mail';
create table :dbname.users_addresses
       (
        address_parts_ts tsvector not null,
        constraint users_addresses_pk primary key (id),
        constraint users_addresses_unq unique (person_user_ref, entry, locality, country),
        constraint users_addresses_users_fk foreign key (person_user_ref) references :dbname.users(id)
       )
inherits (:dbname.template_people_users_comm_common, :dbname.template_people_users_rel_common, :dbname.template_people_users_addr_common);
comment on table :dbname.users_addresses is 'Users addresses';
comment on column :dbname.users_addresses.address_parts_ts is 'tsvector column used to search an address part';
comment on column :dbname.users_addresses.id is 'Unique identifier of a user address';
comment on column :dbname.users_addresses.person_user_ref is 'Reference of the user concerned - id field of users table';
comment on column :dbname.users_addresses.po_box is 'PO Box';
comment on column :dbname.users_addresses.extended_address is 'Address extension: State, zip code suffix,...';
comment on column :dbname.users_addresses.entry is 'Street address';
comment on column :dbname.users_addresses.locality is 'Locality';
comment on column :dbname.users_addresses.country is 'Country';
comment on column :dbname.users_addresses.region is 'Region';
comment on column :dbname.users_addresses.zip_code is 'Zip code';
comment on column :dbname.users_addresses.organization_unit is 'When a physical user is in relationship with a moral one, indicates the department or unit the user is related to';
comment on column :dbname.users_addresses.person_user_role is 'User role in the organization referenced';
comment on column :dbname.users_addresses.activity_period is 'Main user activity period or user activity period in the organization referenced';
comment on column :dbname.users_addresses.comm_type is 'Type of communication table concerned: address, phone or e-mail';
create table :dbname.users_login_infos
       (
        user_ref integer not null,
        login_type varchar default 'local' not null,
        user_name varchar,
        password varchar,
        system_id varchar,
        db_user_type smallint default 1 not null,
        constraint users_login_infos_unq unique (user_ref, login_type),
        constraint users_login_infos_users_fk foreign key (user_ref) references :dbname.users(id),
        constraint users_login_infos_login_type_chk check (login_type in ('local', 'shibbolet', 'openID', 'ldap', 'kerberos'))
       );
comment on table :dbname.users_login_infos is 'Contains the login/password informations of DaRWIN 2 users';
comment on column :dbname.users_login_infos.user_ref is 'Identifier of user - id field of users table';
comment on column :dbname.users_login_infos.login_type is 'Type of identification system';
comment on column :dbname.users_login_infos.user_name is 'For some system (local, ldap, kerberos,...) provides the username (encrypted form)';
comment on column :dbname.users_login_infos.password is 'For some system (local, ldap, kerberos,...) provides the password (encrypted form)';
comment on column :dbname.users_login_infos.system_id is 'For some system (shibbolet, openID,...) provides the user id';
comment on column :dbname.users_login_infos.db_user_type is 'Integer is representing a role: 1 for registered user, 2 for encoder, 4 for collection manager, 8 for system admin,...';
create table :dbname.template_people_users_multimedia
       (
        person_user_ref integer not null,
        object_ref integer not null,
        category varchar default 'avatar' not null,
        constraint template_people_users_multimedia_fk foreign key (object_ref) references :dbname.multimedia(id),
        constraint template_people_users_multimedia_category_chk check (category in ('avatar', 'spelled name', 'url'))
       );
comment on table :dbname.template_people_users_multimedia is 'Template table used to construct people/users associated multimedia table';
comment on column :dbname.template_people_users_multimedia.person_user_ref is 'Reference of person/user - id field of people/users table';
comment on column :dbname.template_people_users_multimedia.object_ref is 'Reference of multimedia object - id field of multimedia table';
comment on column :dbname.template_people_users_multimedia.category is 'Category qualifying the multimedia object use for this person';
create table :dbname.people_multimedia
       (
        constraint people_multimedia_unq unique (person_user_ref, object_ref, category),
        constraint people_multimedia_people_fk foreign key (person_user_ref) references :dbname.people(id)
       )
inherits (:dbname.template_people_users_multimedia);
comment on table :dbname.people_multimedia is 'Multimedia objects linked to persons';
comment on column :dbname.people_multimedia.person_user_ref is 'Reference of person concerned - id field of people table';
comment on column :dbname.people_multimedia.object_ref is 'Reference of multimedia object associated - id field of multimedia table';
comment on column :dbname.people_multimedia.category is 'Object catégory: avatar, spelled name,...';
create table :dbname.users_multimedia
       (
        constraint users_multimedia_unq unique (person_user_ref, object_ref, category),
        constraint users_multimedia_users_fk foreign key (person_user_ref) references :dbname.users(id)
       )
inherits (:dbname.template_people_users_multimedia);
comment on table :dbname.users_multimedia is 'Multimedia objects linked to users';
comment on column :dbname.users_multimedia.person_user_ref is 'Reference of user concerned - id field of users table';
comment on column :dbname.users_multimedia.object_ref is 'Reference of multimedia object associated - id field of multimedia table';
comment on column :dbname.users_multimedia.category is 'Object catégory: avatar, spelled name,...';
create table :dbname.collections
       (
        id serial not null,
        code varchar not null,
        name varchar not null,
        institution_ref integer not null,
        parent_collection_ref integer,
        path varchar not null,
        constraint collections_pk primary key (id),
        constraint collections_institutions_fk foreign key (institution_ref) references :dbname.people(id),
        constraint collections_collections_fk foreign key (parent_collection_ref) references :dbname.collections(id),
        constraint collections_unq unique (path)
       );
comment on table :dbname.collections is 'List of all collections encoded in DaRWIN 2';
comment on column :dbname.collections.id is 'Unique identifier of a collection';
comment on column :dbname.collections.code is 'Code given to collection';
comment on column :dbname.collections.name is 'Collection name';
comment on column :dbname.collections.institution_ref is 'Reference of institution current collection belongs to - id field of people table';
comment on column :dbname.collections.parent_collection_ref is 'Recursive reference to collection table itself to represent collection parenty/hierarchy';
comment on column :dbname.collections.path is 'Descriptive path for collection hierarchy, each level separated by a /';
create table :dbname.template_collections_users
       (
        collection_ref integer default 0 not null,
        user_ref integer default 0 not null
       );
comment on table :dbname.template_collections_users is 'Template table used to construct collections rights tables';
comment on column :dbname.template_collections_users.collection_ref is 'Reference of collection concerned - id field of collections table';
comment on column :dbname.template_collections_users.user_ref is 'Reference of user - id field of users table';
create table :dbname.collections_admin
       (
        main_mgr boolean default true not null,
        constraint collections_admin_unq unique (collection_ref, user_ref),
        constraint collections_admin_collections_fk foreign key (collection_ref) references :dbname.collections(id),
        constraint collections_admin_users_fk foreign key (user_ref) references :dbname.users(id)
       )
inherits (:dbname.template_collections_users);
comment on table :dbname.collections_admin is 'Stores the list of collections administrators';
comment on column :dbname.collections_admin.collection_ref is 'Reference of collection concerned - id field of collections table';
comment on column :dbname.collections_admin.user_ref is 'Reference of user - id field of users table';
comment on column :dbname.collections_admin.main_mgr is 'Flag telling if the user selected is the main manager of collection introduced';
create table :dbname.collections_rights
       (
        rights smallint default 1 not null,
        constraint collections_rights_users_fk foreign key (user_ref) references :dbname.users(id),
        constraint collections_rights_collections_fk foreign key (collection_ref) references :dbname.collections(id),
        constraint collections_rights_unq unique (collection_ref, user_ref)
       )
inherits (:dbname.template_collections_users);
comment on table :dbname.collections_rights is 'List of rights of given users on given collections';
comment on column :dbname.collections_rights.rights is 'Integer value resulting of the sum of integers from an arithmetic suite. Each number of this suite represent a right on the collection: 1 for read, 2 for insert, 4 for update and 8 for delete - A rights value of 11 means read-insert-delete (1+2+8)';
comment on column :dbname.collections_rights.collection_ref is 'Reference of collection concerned - id field of collections table';
comment on column :dbname.collections_rights.user_ref is 'Reference of user - id field of users table';
create table :dbname.collections_fields_visibilities
       (
        field_group_name varchar not null,
        db_user_type smallint default 1 not null,
        searchable boolean default true not null,
        visible boolean default true not null,
        constraint collections_fields_visibilities_unq unique (collection_ref, user_ref, field_group_name, db_user_type),
        constraint collections_fields_visibilities_collections_fk foreign key (collection_ref) references :dbname.collections(id),
        constraint collections_fields_visibilities_users_fk foreign key (user_ref) references :dbname.users(id)
       )
inherits (:dbname.template_collections_users);
comment on table :dbname.collections_fields_visibilities is 'This table tells which group of fields can be searchable and/or visible by a user role or a given precise user - for specimens tables, give the possibility to manage these field visibilities per collections';
comment on column :dbname.collections_fields_visibilities.collection_ref is 'Reference of collection concerned - id field of collections table';
comment on column :dbname.collections_fields_visibilities.user_ref is 'Reference of user - id field of users table';
comment on column :dbname.collections_fields_visibilities.field_group_name is 'Group of fields name';
comment on column :dbname.collections_fields_visibilities.db_user_type is 'Integer is representing a role: 1 for registered user, 2 for encoder, 4 for collection manager, 8 for system admin,...';
comment on column :dbname.collections_fields_visibilities.searchable is 'Flag telling if the field group is searchable - meaning these fields will appear as search criterias in the search form';
comment on column :dbname.collections_fields_visibilities.visible is 'Flag telling if the field group is visible - meaning these fields will be displayable in the result table';
create table :dbname.users_coll_rights_asked
       (
        field_group_name varchar not null,
        db_user_type smallint not null,
        searchable boolean default true not null,
        visible boolean default true not null,
        motivation varchar not null,
        asking_date_time update_date_time,
        with_sub_collections boolean default true not null,
        constraint users_coll_rights_asked_unq unique (collection_ref, user_ref, field_group_name, db_user_type),
        constraint users_coll_rights_asked_collections_fk foreign key (collection_ref) references :dbname.collections(id),
        constraint users_coll_rights_asked_users_fk foreign key (user_ref) references :dbname.users(id)
       )
inherits (:dbname.template_collections_users);
comment on table :dbname.users_coll_rights_asked is 'List all rights asked by a registered user or encoder to collection managers';
comment on column :dbname.users_coll_rights_asked.collection_ref is 'Reference of collection concerned - id field of collections table';
comment on column :dbname.users_coll_rights_asked.user_ref is 'Reference of user - id field of users table';
comment on column :dbname.users_coll_rights_asked.field_group_name is 'Group of fields name';
comment on column :dbname.users_coll_rights_asked.db_user_type is 'Integer is representing a role: 1 for registered user, 2 for encoder, 4 for collection manager, 8 for system admin,...';
comment on column :dbname.users_coll_rights_asked.searchable is 'Flag telling if the field group is searchable - meaning these fields will appear as search criterias in the search form';
comment on column :dbname.users_coll_rights_asked.visible is 'Flag telling if the field group is visible - meaning these fields will be displayable in the result table';
comment on column :dbname.users_coll_rights_asked.motivation is 'Motivation given by asker';
comment on column :dbname.users_coll_rights_asked.asking_date_time is 'Telling when right ask was done';
comment on column :dbname.users_coll_rights_asked.with_sub_collections is 'Rights are asked on a single collection or on this collection with all the sub-collections included ?';
create table :dbname.record_visibilities
       (
        db_user_type smallint default 1 not null,
        user_ref integer not null,
        visible boolean default true not null,
        constraint record_visibilities_unq unique (table_ref, record_id, user_ref, db_user_type),
        constraint record_visibilities_table_list_fk foreign key (table_ref) references :dbname.table_list(id),
        constraint record_visibilities_users_fk foreign key (user_ref) references :dbname.users(id)
       )
inherits (:dbname.template_table_record_ref);
comment on table :dbname.record_visibilities is 'Manage visibility of records for all DaRWIN 2 tables - visibility per user type and/or specific user';
comment on column :dbname.record_visibilities.user_ref is 'Reference of user - id field of users table';
comment on column :dbname.record_visibilities.db_user_type is 'Integer is representing a role: 1 for registered user, 2 for encoder, 4 for collection manager, 8 for system admin,...';
comment on column :dbname.record_visibilities.table_ref is 'Reference of table concerned - id field of table_list table';
comment on column :dbname.record_visibilities.record_id is 'ID of record a visibility is defined for';
comment on column :dbname.record_visibilities.visible is 'Flag telling if record is visible or not';
create table :dbname.users_workflow
       (
        user_ref integer not null,
        status varchar default 'to check' not null,
        modification_date_time update_date_time,
        comment varchar,
        constraint users_workflow_table_list_fk foreign key (table_ref) references :dbname.table_list(id),
        constraint users_workflow_users_fk foreign key (user_ref) references :dbname.users(id),
        constraint users_workflow_status_chk check (status in ('to check', 'to be corrected', 'published'))
       )
inherits (:dbname.template_table_record_ref);
comment on table :dbname.users_workflow is 'Workflow information for each record encoded';
comment on column :dbname.users_workflow.user_ref is 'Reference of user - id field of users table';
comment on column :dbname.users_workflow.table_ref is 'Reference of table concerned - id field of table_list table';
comment on column :dbname.users_workflow.record_id is 'ID of record a workflow is defined for';
comment on column :dbname.users_workflow.status is 'Record status: to correct, to be corrected or published';
comment on column :dbname.users_workflow.modification_date_time is 'Date and time of status change - last date/time is used as actual status, but helps also to keep an history of status change';
comment on column :dbname.users_workflow.comment is 'Complementary comments';
create table :dbname.users_tables_fields_tracked
       (
        table_ref integer not null,
        field_ref integer not null,
        user_ref integer not null,
        constraint users_tables_fields_tracked_unq unique (table_ref, field_ref, user_ref),
        constraint users_tables_fields_tracked_table_list_fk foreign key (table_ref) references :dbname.table_list(id),
        constraint users_tables_fields_tracked_field_list_fk foreign key (field_ref) references :dbname.field_list(id),
        constraint users_tables_fields_tracked_users_fk foreign key (user_ref) references :dbname.users(id)
       );
comment on table :dbname.users_tables_fields_tracked is 'List fields tracked per user';
comment on column :dbname.users_tables_fields_tracked.user_ref is 'Reference of user - id field of users table';
comment on column :dbname.users_tables_fields_tracked.table_ref is 'Reference of table concerned - id field of table_list table';
comment on column :dbname.users_tables_fields_tracked.field_ref is 'Reference of field tracked - id field of field_list table';
create table users_tracking
       (
        id bigserial not null,
        user_ref integer not null,
        action varchar default 'insert' not null,
        modification_date_time update_date_time,
        constraint users_tracking_pk primary key (id),
        constraint users_tracking_table_list_fk foreign key (table_ref) references :dbname.table_list(id),
        constraint users_tracking_users_fk foreign key (user_ref) references :dbname.users(id),
        constraint users_action_chk check (action in ('insert', 'update', 'delete', 'printed'))
       )
inherits (:dbname.template_table_record_ref);
comment on table :dbname.users_tracking is 'Tracking of users actions on tables';
comment on column :dbname.users_tracking.table_ref is 'Reference of table concerned - id field of table_list table';
comment on column :dbname.users_tracking.record_id is 'ID of record concerned';
comment on column :dbname.users_tracking.id is 'Unique identifier of a table track entry';
comment on column :dbname.users_tracking.user_ref is 'Reference of user having made an action - id field of users table';
comment on column :dbname.users_tracking.action is 'Action done on table record';
comment on column :dbname.users_tracking.modification_date_time is 'Track date and time';
create table :dbname.users_tracking_records
       (
        tracking_ref bigint not null,
        field_ref integer not null,
        old_value varchar[],
        new_value varchar[],
        constraint users_tracking_records_unq unique (tracking_ref, field_ref),
        constraint users_tracking_records_users_tracking_fk foreign key (tracking_ref) references :dbname.users_tracking(id),
        constraint users_tracking_records_field_list_fk foreign key (field_ref) references :dbname.field_list(id)
       );
comment on table :dbname.users_tracking_records is 'Track of fields modification per record';
comment on column :dbname.users_tracking_records.tracking_ref is 'Reference of tracking entry - id field of users_tracking table';
comment on column :dbname.users_tracking_records.field_ref is 'Reference of field concerned - id field of field_list table';
comment on column :dbname.users_tracking_records.old_value is 'Old value when an update is done - array of values when field modified is an array';
comment on column :dbname.users_tracking_records.new_value is 'New value when an update is done - array of values when field modified is an array';
create table :dbname.collection_maintenance
       (
        user_ref integer not null,
        category varchar default 'action' not null,
        action_observation varchar not null,
        description varchar,
        description_ts tsvector,
        language_full_text full_text_language,
        modification_date_time update_date_time,
        constraint collection_maintenance_table_list_fk foreign key (table_ref) references :dbname.table_list(id),
        constraint collection_maintenance_users_fk foreign key (user_ref) references :dbname.users(id),
        constraint collection_maintenance_category_chk check (category in ('action', 'observation'))
       )
inherits (:dbname.template_table_record_ref);
comment on table :dbname.collection_maintenance is 'History of specimen maintenance';
comment on column :dbname.collection_maintenance.table_ref is 'Reference of table a maintenance entry has been created for';
comment on column :dbname.collection_maintenance.record_id is 'ID of record a maintenance entry has been created for';
comment on column :dbname.collection_maintenance.user_ref is 'Reference of user having done an action or an observation';
comment on column :dbname.collection_maintenance.category is 'Action or Observation';
comment on column :dbname.collection_maintenance.action_observation is 'Action or observation done';
comment on column :dbname.collection_maintenance.description is 'Complementary description';
comment on column :dbname.collection_maintenance.description_ts is 'tsvector form of description field';
comment on column :dbname.collection_maintenance.language_full_text is 'Language used by to_tsvector full text search function';
create table :dbname.my_saved_searches
       (
        user_ref integer not null,
        name varchar default 'default' not null,
        search_criterias varchar[] not null,
        favorite boolean default false not null,
        modification_date_time update_date_time,
        visible_fields_in_result varchar[] not null,
        constraint my_saved_searches_unq unique (user_ref, name),
        constraint my_saved_searches_users_fk foreign key (user_ref) references :dbname.users(id)
       );
comment on table :dbname.my_saved_searches is 'Stores user''s saved searches but also (by default) the last search done';
comment on column :dbname.my_saved_searches.user_ref is 'Reference of user having saved a search';
comment on column :dbname.my_saved_searches.name is 'Name given by user to his/her saved search';
comment on column :dbname.my_saved_searches.search_criterias is 'Array of criterias and values passed to search engine';
comment on column :dbname.my_saved_searches.favorite is 'Flag telling if saved search concerned is one of the favorites or not';
comment on column :dbname.my_saved_searches.modification_date_time is 'Last modification or entry date and time';
comment on column :dbname.my_saved_searches.visible_fields_in_result is 'Array of fields that were set visible in the result table at the time the search was saved';
create table :dbname.my_preferences
       (
        user_ref integer not null,
        category varchar default 'board_widget' not null,
        group_name varchar not null,
        order_by smallint default 1 not null,
        col_num smallint default 1 not null,
        mandatory boolean default false not null,
        opened boolean default true not null,
        color varchar default '#5BAABD' not null,
        icon_ref integer,
        title_perso varchar(32),
        constraint my_preferences_unq unique (user_ref, category, group_name),
        constraint my_preferences_users_fk foreign key (user_ref) references :dbname.users(id),
        constraint my_preferences_multimedia_fk foreign key (icon_ref) references :dbname.multimedia(id),
        constraint my_preferences_category_chk check (category in ('board_widget', 'encoding_widget'))
       );
comment on table :dbname.my_preferences is 'Stores user''s preferences for customizable page elements - widgets mainly';
comment on column :dbname.my_preferences.user_ref is 'Reference of user concerned - id field of users table';
comment on column :dbname.my_preferences.category is 'Customizable page element category: board widget, encoding widget,...';
comment on column :dbname.my_preferences.group_name is 'Customizable page element name';
comment on column :dbname.my_preferences.order_by is 'Absolute order by between page element name';
comment on column :dbname.my_preferences.col_num is 'Column number - tells in which column the page element concerned is';
comment on column :dbname.my_preferences.mandatory is 'Flag telling if the page element can be closed or not';
comment on column :dbname.my_preferences.opened is 'Flag telling if the page element is opened by default or not';
comment on column :dbname.my_preferences.color is 'Color given to page element by user';
comment on column :dbname.my_preferences.icon_ref is 'Reference of multimedia icon to be used before page element title';
comment on column :dbname.my_preferences.title_perso is 'Page element title given by user';
create table :dbname.my_saved_specimens
       (
        user_ref integer not null,
        name varchar not null,
        specimen_ids integer[] not null,
        favorite boolean default false not null,
        modification_date_time update_date_time,
        constraint my_saved_specimens_unq unique (user_ref, name),
        constraint my_saved_specimens_users_fk foreign key (user_ref) references :dbname.users(id)
       );
comment on table :dbname.my_saved_specimens is 'List of specimens selection made by users - sort of suitcases for personal selections';
comment on column :dbname.my_saved_specimens.user_ref is 'Reference of user - id field of users table';
comment on column :dbname.my_saved_specimens.name is 'Name given to this selection by user';
comment on column :dbname.my_saved_specimens.specimen_ids is 'Array of ids of all specimens selected';
comment on column :dbname.my_saved_specimens.favorite is 'Flag telling the selection is one of the favorites or not';
comment on column :dbname.my_saved_specimens.modification_date_time is 'Last update date and time';
create table :dbname.template_classifications
       (
        id serial not null,
        name varchar not null,
        name_indexed varchar not null,
        description_year smallint,
        description_year_compl char(2),
        level_ref integer not null,
        status varchar default 'valid' not null,
        full_hierarchy_path varchar not null,
        partial_hierarchy_path varchar not null,
        constraint template_classifications_status_chk check (status in ('valid', 'invalid', 'in discussion'))
       );
comment on table :dbname.template_classifications is 'Template table used to construct every common data in each classifications tables (taxonomy, chronostratigraphy, lithostratigraphy,...)';
comment on column :dbname.template_classifications.id is 'Unique identifier of a classification unit';
comment on column :dbname.template_classifications.name is 'Classification unit name';
comment on column :dbname.template_classifications.name_indexed is 'Indexed form of name field';
comment on column :dbname.template_classifications.description_year is 'Year of description';
comment on column :dbname.template_classifications.description_year_compl is 'Complement to year of description: a, b, c, ...';
comment on column :dbname.template_classifications.level_ref is 'Reference of classification level the unit is encoded in';
comment on column :dbname.template_classifications.status is 'Validitiy status: valid, invalid, in discussion';
comment on column :dbname.template_classifications.full_hierarchy_path is 'Hierarchy path composed of parents ids and unit name - used for unique indexation';
comment on column :dbname.template_classifications.partial_hierarchy_path is 'Partial hierarchy path composed of non 0 parents ids -> reflexion of real hierarchy path for treeview ease of construction';
create table :dbname.taxa
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
        constraint taxa_pk primary key (id),
        constraint taxa_unq unique (full_hierarchy_path),
        constraint taxa_catalogue_levels_fk foreign key (level_ref) references :dbname.catalogue_levels(id),
        constraint taxa_taxa_domain_fk foreign key (domain_ref) references :dbname.taxa(id),
        constraint taxa_taxa_super_phylum_fk foreign key (super_phylum_ref) references :dbname.taxa(id),
        constraint taxa_taxa_phylum_fk foreign key (phylum_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_phylum_fk foreign key (sub_phylum_ref) references :dbname.taxa(id),
        constraint taxa_taxa_infra_phylum_fk foreign key (infra_phylum_ref) references :dbname.taxa(id),
        constraint taxa_taxa_super_cohort_botany_fk foreign key (super_cohort_botany_ref) references :dbname.taxa(id),
        constraint taxa_taxa_cohort_botany_fk foreign key (cohort_botany_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_cohort_botany_fk foreign key (sub_cohort_botany_ref) references :dbname.taxa(id),
        constraint taxa_taxa_infra_cohort_botany_fk foreign key (infra_cohort_botany_ref) references :dbname.taxa(id),
        constraint taxa_taxa_super_class_fk foreign key (super_class_ref) references :dbname.taxa(id),
        constraint taxa_taxa_class_fk foreign key (class_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_class_fk foreign key (sub_class_ref) references :dbname.taxa(id),
        constraint taxa_taxa_infra_class_fk foreign key (infra_class_ref) references :dbname.taxa(id),
        constraint taxa_taxa_super_division_fk foreign key (super_division_ref) references :dbname.taxa(id),
        constraint taxa_taxa_division_fk foreign key (division_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_division_fk foreign key (sub_division_ref) references :dbname.taxa(id),
        constraint taxa_taxa_infra_division_fk foreign key (infra_division_ref) references :dbname.taxa(id),
        constraint taxa_taxa_super_legion_fk foreign key (super_legion_ref) references :dbname.taxa(id),
        constraint taxa_taxa_legion_fk foreign key (legion_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_legion_fk foreign key (sub_legion_ref) references :dbname.taxa(id),
        constraint taxa_taxa_infra_legion_fk foreign key (infra_legion_ref) references :dbname.taxa(id),
        constraint taxa_taxa_super_cohort_zool_fk foreign key (super_cohort_zool_ref) references :dbname.taxa(id),
        constraint taxa_taxa_cohort_zool_fk foreign key (cohort_zool_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_cohort_zool_fk foreign key (sub_cohort_zool_ref) references :dbname.taxa(id),
        constraint taxa_taxa_infra_cohort_zool_fk foreign key (infra_cohort_zool_ref) references :dbname.taxa(id),
        constraint taxa_taxa_super_order_fk foreign key (super_order_ref) references :dbname.taxa(id),
        constraint taxa_taxa_order_fk foreign key (order_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_order_fk foreign key (sub_order_ref) references :dbname.taxa(id),
        constraint taxa_taxa_infra_order_fk foreign key (infra_order_ref) references :dbname.taxa(id),
        constraint taxa_taxa_section_zool_fk foreign key (section_zool_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_section_zool_fk foreign key (sub_section_zool_ref) references :dbname.taxa(id),
        constraint taxa_taxa_super_family_fk foreign key (super_family_ref) references :dbname.taxa(id),
        constraint taxa_taxa_family_fk foreign key (family_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_family_fk foreign key (sub_family_ref) references :dbname.taxa(id),
        constraint taxa_taxa_infra_family_fk foreign key (infra_family_ref) references :dbname.taxa(id),
        constraint taxa_taxa_super_tribe_fk foreign key (super_tribe_ref) references :dbname.taxa(id),
        constraint taxa_taxa_tribe_fk foreign key (tribe_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_tribe_fk foreign key (sub_tribe_ref) references :dbname.taxa(id),
        constraint taxa_taxa_infra_tribe_fk foreign key (infra_tribe_ref) references :dbname.taxa(id),
        constraint taxa_taxa_genus_fk foreign key (genus_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_genus_fk foreign key (sub_genus_ref) references :dbname.taxa(id),
        constraint taxa_taxa_section_botany_fk foreign key (section_botany_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_section_botany_fk foreign key (sub_section_botany_ref) references :dbname.taxa(id),
        constraint taxa_taxa_serie_fk foreign key (serie_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_serie_fk foreign key (sub_serie_ref) references :dbname.taxa(id),
        constraint taxa_taxa_super_species_fk foreign key (super_species_ref) references :dbname.taxa(id),
        constraint taxa_taxa_species_fk foreign key (species_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_species_fk foreign key (sub_species_ref) references :dbname.taxa(id),
        constraint taxa_taxa_variety_fk foreign key (variety_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_variety_fk foreign key (sub_variety_ref) references :dbname.taxa(id),
        constraint taxa_taxa_form_fk foreign key (form_ref) references :dbname.taxa(id),
        constraint taxa_taxa_sub_form_fk foreign key (sub_form_ref) references :dbname.taxa(id),
        constraint taxa_taxa_abberans_fk foreign key (abberans_ref) references :dbname.taxa(id)
       )
inherits (:dbname.template_classifications);
comment on table :dbname.taxa is 'Taxonomic classification table';
comment on column :dbname.taxa.id is 'Unique identifier of a classification unit';
comment on column :dbname.taxa.name is 'Classification unit name';
comment on column :dbname.taxa.name_indexed is 'Indexed form of name field';
comment on column :dbname.taxa.description_year is 'Year of description';
comment on column :dbname.taxa.description_year_compl is 'Complement to year of description: a, b, c, ...';
comment on column :dbname.taxa.level_ref is 'Reference of classification level the unit is encoded in';
comment on column :dbname.taxa.status is 'Validitiy status: valid, invalid, in discussion';
comment on column :dbname.taxa.full_hierarchy_path is 'Hierarchy path composed of parents ids and unit name - used for unique indexation';
comment on column :dbname.taxa.partial_hierarchy_path is 'Partial hierarchy path composed of non 0 parents ids -> reflexion of real hierarchy path for treeview ease of construction';
comment on column :dbname.taxa.domain_ref is 'Reference of domain the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.domain_indexed is 'Indexed name of domain the current taxa depends of';
comment on column :dbname.taxa.super_phylum_ref is 'Reference of super_phylum the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.super_phylum_indexed is 'Indexed name of super_phylum the current taxa depends of';
comment on column :dbname.taxa.phylum_ref is 'Reference of phylum the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.phylum_indexed is 'Indexed name of phylum the current taxa depends of';
comment on column :dbname.taxa.sub_phylum_ref is 'Reference of sub phylum the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_phylum_indexed is 'Indexed name of sub phylum the current taxa depends of';
comment on column :dbname.taxa.infra_phylum_ref is 'Reference of infra phylum the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.infra_phylum_indexed is 'Indexed name of infra phylum the current taxa depends of';
comment on column :dbname.taxa.super_cohort_botany_ref is 'Reference of super cohort botany the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.super_cohort_botany_indexed is 'Indexed name of super cohort botany the current taxa depends of';
comment on column :dbname.taxa.cohort_botany_ref is 'Reference of cohort botany the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.cohort_botany_indexed is 'Indexed name of cohort botany the current taxa depends of';
comment on column :dbname.taxa.sub_cohort_botany_ref is 'Reference of sub cohort botany the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_cohort_botany_indexed is 'Indexed name of sub cohort botany the current taxa depends of';
comment on column :dbname.taxa.infra_cohort_botany_ref is 'Reference of infra cohort botany the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.infra_cohort_botany_indexed is 'Indexed name of infra cohort botany the current taxa depends of';
comment on column :dbname.taxa.super_class_ref is 'Reference of super class the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.super_class_indexed is 'Indexed name of super class the current taxa depends of';
comment on column :dbname.taxa.class_ref is 'Reference of class the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.class_indexed is 'Indexed name of class the current taxa depends of';
comment on column :dbname.taxa.sub_class_ref is 'Reference of sub class the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_class_indexed is 'Indexed name of sub class the current taxa depends of';
comment on column :dbname.taxa.infra_class_ref is 'Reference of infra class the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.infra_class_indexed is 'Indexed name of infra class the current taxa depends of';
comment on column :dbname.taxa.super_division_ref is 'Reference of super division the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.super_division_indexed is 'Indexed name of super division the current taxa depends of';
comment on column :dbname.taxa.division_ref is 'Reference of division the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.division_indexed is 'Indexed name of division the current taxa depends of';
comment on column :dbname.taxa.sub_division_ref is 'Reference of sub division the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_division_indexed is 'Indexed name of sub division the current taxa depends of';
comment on column :dbname.taxa.infra_division_ref is 'Reference of infra division the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.infra_division_indexed is 'Indexed name of infra division the current taxa depends of';
comment on column :dbname.taxa.super_legion_ref is 'Reference of super legion the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.super_legion_indexed is 'Indexed name of super legion the current taxa depends of';
comment on column :dbname.taxa.legion_ref is 'Reference of legion the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.legion_indexed is 'Indexed name of legion the current taxa depends of';
comment on column :dbname.taxa.sub_legion_ref is 'Reference of sub legion the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_legion_indexed is 'Indexed name of sub legion the current taxa depends of';
comment on column :dbname.taxa.infra_legion_ref is 'Reference of infra legion the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.infra_legion_indexed is 'Indexed name of infra legion the current taxa depends of';
comment on column :dbname.taxa.super_cohort_zool_ref is 'Reference of super cohort zool the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.super_cohort_zool_indexed is 'Indexed name of super cohort zool the current taxa depends of';
comment on column :dbname.taxa.cohort_zool_ref is 'Reference of cohort zool the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.cohort_zool_indexed is 'Indexed name of cohort zool the current taxa depends of';
comment on column :dbname.taxa.sub_cohort_zool_ref is 'Reference of sub cohort zool the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_cohort_zool_indexed is 'Indexed name of sub cohort zool the current taxa depends of';
comment on column :dbname.taxa.infra_cohort_zool_ref is 'Reference of infra cohort zool the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.infra_cohort_zool_indexed is 'Indexed name of infra cohort zool the current taxa depends of';
comment on column :dbname.taxa.super_order_ref is 'Reference of super order the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.super_order_indexed is 'Indexed name of super order the current taxa depends of';
comment on column :dbname.taxa.order_ref is 'Reference of order the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.order_indexed is 'Indexed name of order the current taxa depends of';
comment on column :dbname.taxa.sub_order_ref is 'Reference of sub order the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_order_indexed is 'Indexed name of sub order the current taxa depends of';
comment on column :dbname.taxa.infra_order_ref is 'Reference of infra order the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.infra_order_indexed is 'Indexed name of infra order the current taxa depends of';
comment on column :dbname.taxa.section_zool_ref is 'Reference of section zool the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.section_zool_indexed is 'Indexed name of section zool the current taxa depends of';
comment on column :dbname.taxa.sub_section_zool_ref is 'Reference of sub section zool the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_section_zool_indexed is 'Indexed name of sub section zool the current taxa depends of';
comment on column :dbname.taxa.super_family_ref is 'Reference of super family the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.super_family_indexed is 'Indexed name of super family the current taxa depends of';
comment on column :dbname.taxa.family_ref is 'Reference of family the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.family_indexed is 'Indexed name of family the current taxa depends of';
comment on column :dbname.taxa.sub_family_ref is 'Reference of sub family the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_family_indexed is 'Indexed name of sub family the current taxa depends of';
comment on column :dbname.taxa.infra_family_ref is 'Reference of infra family the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.infra_family_indexed is 'Indexed name of infra family the current taxa depends of';
comment on column :dbname.taxa.super_tribe_ref is 'Reference of super tribe the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.super_tribe_indexed is 'Indexed name of super tribe the current taxa depends of';
comment on column :dbname.taxa.tribe_ref is 'Reference of tribe the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.tribe_indexed is 'Indexed name of tribe the current taxa depends of';
comment on column :dbname.taxa.sub_tribe_ref is 'Reference of sub tribe the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_tribe_indexed is 'Indexed name of sub tribe the current taxa depends of';
comment on column :dbname.taxa.infra_tribe_ref is 'Reference of infra tribe the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.infra_tribe_indexed is 'Indexed name of infra tribe the current taxa depends of';
comment on column :dbname.taxa.genus_ref is 'Reference of genus the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.genus_indexed is 'Indexed name of genus the current taxa depends of';
comment on column :dbname.taxa.sub_genus_ref is 'Reference of sub genus the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_genus_indexed is 'Indexed name of sub genus the current taxa depends of';
comment on column :dbname.taxa.section_botany_ref is 'Reference of section botany the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.section_botany_indexed is 'Indexed name of section botany the current taxa depends of';
comment on column :dbname.taxa.sub_section_botany_ref is 'Reference of sub section botany the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_section_botany_indexed is 'Indexed name of sub section botany the current taxa depends of';
comment on column :dbname.taxa.serie_ref is 'Reference of series the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.serie_indexed is 'Indexed name of series the current taxa depends of';
comment on column :dbname.taxa.sub_serie_ref is 'Reference of sub series the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_serie_indexed is 'Indexed name of sub series the current taxa depends of';
comment on column :dbname.taxa.super_species_ref is 'Reference of super species the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.super_species_indexed is 'Indexed name of super species the current taxa depends of';
comment on column :dbname.taxa.species_ref is 'Reference of species the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.species_indexed is 'Indexed name of species the current taxa depends of';
comment on column :dbname.taxa.sub_species_ref is 'Reference of sub species the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_species_indexed is 'Indexed name of sub species the current taxa depends of';
comment on column :dbname.taxa.variety_ref is 'Reference of variety the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.variety_indexed is 'Indexed name of variety the current taxa depends of';
comment on column :dbname.taxa.sub_variety_ref is 'Reference of sub variety the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_variety_indexed is 'Indexed name of sub variety the current taxa depends of';
comment on column :dbname.taxa.form_ref is 'Reference of form the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.form_indexed is 'Indexed name of form the current taxa depends of';
comment on column :dbname.taxa.sub_form_ref is 'Reference of sub form the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.sub_form_indexed is 'Indexed name of sub form the current taxa depends of';
comment on column :dbname.taxa.abberans_ref is 'Reference of abberans the current taxa depends of - id field of taxa table - recursive reference';
comment on column :dbname.taxa.abberans_indexed is 'Indexed name of abberans the current taxa depends of';
comment on column :dbname.taxa.chimera_hybrid_pos is 'Chimera or Hybrid informations';
create table :dbname.people_taxonomic_names
       (
        person_ref integer not null,
        taxonomic_top_ref integer default 0 not null,
        person_name varchar not null,
        constraint people_taxonomic_names_unq unique (person_ref, taxonomic_top_ref, person_name),
        constraint people_taxonomic_names_taxa_fk foreign key (taxonomic_top_ref) references :dbname.taxa(id),
        constraint people_taxonomic_names_people_fk foreign key (person_ref) references :dbname.people(id)
       );
comment on table people_taxonomic_names is 'Name translation depending on taxonomic top group studied: in botany, Liné will be written L. and in zoology, Liné will be written Linaeus';
comment on column people_taxonomic_names.person_ref is 'Reference of the person concerned - id field of people table';
comment on column people_taxonomic_names.taxonomic_top_ref is 'Reference of the top taxonomic group concerned - id field of taxa table';
comment on column people_taxonomic_names.person_name is 'Person name for the group concerned';
create table :dbname.chronostratigraphy
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
        year_range_unit varchar default 'mma' not null,
        constraint chronostratigraphy_pk primary key (id),
        constraint chronostratigraphy_unq unique (full_hierarchy_path),
        constraint chronostratigraphy_catalogue_levels_fk foreign key (level_ref) references :dbname.catalogue_levels(id),
        constraint chronostratigraphy_chronostratigraphy_eon_fk foreign key (eon_ref) references :dbname.chronostratigraphy(id),
        constraint chronostratigraphy_chronostratigraphy_era_fk foreign key (era_ref) references :dbname.chronostratigraphy(id),
        constraint chronostratigraphy_chronostratigraphy_sub_era_fk foreign key (sub_era_ref) references :dbname.chronostratigraphy(id),
        constraint chronostratigraphy_chronostratigraphy_system_fk foreign key (system_ref) references :dbname.chronostratigraphy(id),
        constraint chronostratigraphy_chronostratigraphy_serie_fk foreign key (serie_ref) references :dbname.chronostratigraphy(id),
        constraint chronostratigraphy_chronostratigraphy_stage_fk foreign key (stage_ref) references :dbname.chronostratigraphy(id),
        constraint chronostratigraphy_chronostratigraphy_sub_stage_fk foreign key (sub_stage_ref) references :dbname.chronostratigraphy(id),
        constraint chronostratigraphy_chronostratigraphy_sub_level_1_fk foreign key (sub_level_1_ref) references :dbname.chronostratigraphy(id),
        constraint chronostratigraphy_chronostratigraphy_sub_level_2_fk foreign key (sub_level_2_ref) references :dbname.chronostratigraphy(id),
        constraint chronostratigraphy_year_range_unit_chk check (year_range_unit in ('a', 'da', 'ma', 'mma', 'mmma', 'ba', 'ta'))
       )
inherits (:dbname.template_classifications);
comment on table :dbname.chronostratigraphy is 'List of chronostratigraphic units';
comment on column :dbname.chronostratigraphy.id is 'Unique identifier of a classification unit';
comment on column :dbname.chronostratigraphy.name is 'Classification unit name';
comment on column :dbname.chronostratigraphy.name_indexed is 'Indexed form of name field';
comment on column :dbname.chronostratigraphy.description_year is 'Year of description';
comment on column :dbname.chronostratigraphy.description_year_compl is 'Complement to year of description: a, b, c, ...';
comment on column :dbname.chronostratigraphy.level_ref is 'Reference of classification level the unit is encoded in';
comment on column :dbname.chronostratigraphy.status is 'Validitiy status: valid, invalid, in discussion';
comment on column :dbname.chronostratigraphy.full_hierarchy_path is 'Hierarchy path composed of parents ids and unit name - used for unique indexation';
comment on column :dbname.chronostratigraphy.partial_hierarchy_path is 'Partial hierarchy path composed of non 0 parents ids -> reflexion of real hierarchy path for treeview ease of construction';
comment on column :dbname.chronostratigraphy.eon_ref is 'Reference of eon the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column :dbname.chronostratigraphy.eon_indexed is 'Indexed name of eon the current unit depends of';
comment on column :dbname.chronostratigraphy.era_ref is 'Reference of era the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column :dbname.chronostratigraphy.era_indexed is 'Indexed name of era the current unit depends of';
comment on column :dbname.chronostratigraphy.sub_era_ref is 'Reference of sub era the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column :dbname.chronostratigraphy.sub_era_indexed is 'Indexed name of sub era the current unit depends of';
comment on column :dbname.chronostratigraphy.system_ref is 'Reference of system the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column :dbname.chronostratigraphy.system_indexed is 'Indexed name of system the current unit depends of';
comment on column :dbname.chronostratigraphy.serie_ref is 'Reference of serie the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column :dbname.chronostratigraphy.serie_indexed is 'Indexed name of serie the current unit depends of';
comment on column :dbname.chronostratigraphy.stage_ref is 'Reference of stage the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column :dbname.chronostratigraphy.stage_indexed is 'Indexed name of stage the current unit depends of';
comment on column :dbname.chronostratigraphy.sub_stage_ref is 'Reference of sub stage the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column :dbname.chronostratigraphy.sub_stage_indexed is 'Indexed name of sub stage the current unit depends of';
comment on column :dbname.chronostratigraphy.sub_level_1_ref is 'Reference of sub level the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column :dbname.chronostratigraphy.sub_level_1_indexed is 'Indexed name of sub level the current unit depends of';
comment on column :dbname.chronostratigraphy.sub_level_2_ref is 'Reference of sub level the current unit depends of - id field of chronostratigraphy table - recursive reference';
comment on column :dbname.chronostratigraphy.sub_level_2_indexed is 'Indexed name of sub level the current unit depends of';
comment on column :dbname.chronostratigraphy.lower_bound is 'Lower age boundary';
comment on column :dbname.chronostratigraphy.upper_bound is 'Upper age boundary';
comment on column :dbname.chronostratigraphy.year_range_unit is 'Unit used for age boundaries';
create table :dbname.lithostratigraphy
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
        constraint lithostratigraphy_pk primary key (id),
        constraint lithostratigraphy_unq unique (full_hierarchy_path),
        constraint lithostratigraphy_catalogue_levels_fk foreign key (level_ref) references :dbname.catalogue_levels(id),
        constraint lithostratigraphy_lithostratigraphy_group_fk foreign key (group_ref) references :dbname.lithostratigraphy(id),
        constraint lithostratigraphy_lithostratigraphy_formation_fk foreign key (formation_ref) references :dbname.lithostratigraphy(id),
        constraint lithostratigraphy_lithostratigraphy_member_fk foreign key (member_ref) references :dbname.lithostratigraphy(id),
        constraint lithostratigraphy_lithostratigraphy_layer_fk foreign key (layer_ref) references :dbname.lithostratigraphy(id),
        constraint lithostratigraphy_lithostratigraphy_sub_level_1_fk foreign key (sub_level_1_ref) references :dbname.lithostratigraphy(id),
        constraint lithostratigraphy_lithostratigraphy_sub_level_2_fk foreign key (sub_level_2_ref) references :dbname.lithostratigraphy(id)
       )
inherits (:dbname.template_classifications);
comment on table :dbname.lithostratigraphy is 'List of lithostratigraphic units';
comment on column :dbname.lithostratigraphy.id is 'Unique identifier of a classification unit';
comment on column :dbname.lithostratigraphy.name is 'Classification unit name';
comment on column :dbname.lithostratigraphy.name_indexed is 'Indexed form of name field';
comment on column :dbname.lithostratigraphy.description_year is 'Year of description';
comment on column :dbname.lithostratigraphy.description_year_compl is 'Complement to year of description: a, b, c, ...';
comment on column :dbname.lithostratigraphy.level_ref is 'Reference of classification level the unit is encoded in';
comment on column :dbname.lithostratigraphy.status is 'Validitiy status: valid, invalid, in discussion';
comment on column :dbname.lithostratigraphy.full_hierarchy_path is 'Hierarchy path composed of parents ids and unit name - used for unique indexation';
comment on column :dbname.lithostratigraphy.partial_hierarchy_path is 'Partial hierarchy path composed of non 0 parents ids -> reflexion of real hierarchy path for treeview ease of construction';
comment on column :dbname.lithostratigraphy.group_ref is 'Reference of group the current unit depends of - id field of lithostratigraphy table - recursive reference';
comment on column :dbname.lithostratigraphy.group_indexed is 'Indexed name of group the current unit depends of';
comment on column :dbname.lithostratigraphy.formation_ref is 'Reference of formation the current unit depends of - id field of lithostratigraphy table - recursive reference';
comment on column :dbname.lithostratigraphy.formation_indexed is 'Indexed name of formation the current unit depends of';
comment on column :dbname.lithostratigraphy.member_ref is 'Reference of member the current unit depends of - id field of lithostratigraphy table - recursive reference';
comment on column :dbname.lithostratigraphy.member_indexed is 'Indexed name of member the current unit depends of';
comment on column :dbname.lithostratigraphy.layer_ref is 'Reference of layer the current unit depends of - id field of lithostratigraphy table - recursive reference';
comment on column :dbname.lithostratigraphy.layer_indexed is 'Indexed name of layer the current unit depends of';
comment on column :dbname.lithostratigraphy.sub_level_1_ref is 'Reference of sub level the current unit depends of - id field of lithostratigraphy table - recursive reference';
comment on column :dbname.lithostratigraphy.sub_level_1_indexed is 'Indexed name of sub level the current unit depends of';
comment on column :dbname.lithostratigraphy.sub_level_2_ref is 'Reference of sub level the current unit depends of - id field of lithostratigraphy table - recursive reference';
comment on column :dbname.lithostratigraphy.sub_level_2_indexed is 'Indexed name of sub level the current unit depends of';
create table :dbname.mineralogy
       (
        code varchar not null,
        classification varchar default 'strunz' not null,
        formule varchar,
        formule_indexed varchar,
        cristal_system varchar,
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
        constraint mineralogy_pk primary key (id),
        constraint mineralogy_unq unique (full_hierarchy_path),
        constraint mineralogy_classification_chk check (classification in ('strunz', 'dana')),
        constraint mineralogy_cristal_system_chk check (cristal_system in ('isometric', 'tetragonal', 'hexagonal', 'orthorhombic', 'trigonal', 'triclinic', 'monoclinic')),
        constraint mineralogy_catalogue_levels_fk foreign key (level_ref) references :dbname.catalogue_levels(id),
        constraint mineralogy_mineralogy_unit_class_fk foreign key (unit_class_ref) references :dbname.mineralogy(id),
        constraint mineralogy_mineralogy_unit_division_fk foreign key (unit_division_ref) references :dbname.mineralogy(id),
        constraint mineralogy_mineralogy_unit_family_fk foreign key (unit_family_ref) references :dbname.mineralogy(id),
        constraint mineralogy_mineralogy_unit_group_fk foreign key (unit_group_ref) references :dbname.mineralogy(id),
        constraint mineralogy_mineralogy_unit_variety_fk foreign key (unit_variety_ref) references :dbname.mineralogy(id)
       )
inherits (:dbname.template_classifications);
comment on table :dbname.mineralogy is 'List of mineralogic units';
comment on column :dbname.mineralogy.id is 'Unique identifier of a classification unit';
comment on column :dbname.mineralogy.name is 'Classification unit name';
comment on column :dbname.mineralogy.name_indexed is 'Indexed form of name field';
comment on column :dbname.mineralogy.description_year is 'Year of description';
comment on column :dbname.mineralogy.description_year_compl is 'Complement to year of description: a, b, c, ...';
comment on column :dbname.mineralogy.level_ref is 'Reference of classification level the unit is encoded in';
comment on column :dbname.mineralogy.status is 'Validitiy status: valid, invalid, in discussion';
comment on column :dbname.mineralogy.full_hierarchy_path is 'Hierarchy path composed of parents ids and unit name - used for unique indexation';
comment on column :dbname.mineralogy.partial_hierarchy_path is 'Partial hierarchy path composed of non 0 parents ids -> reflexion of real hierarchy path for treeview ease of construction';
comment on column :dbname.mineralogy.code is 'Classification code given to mineral - in classification chosen - Strunz by default';
comment on column :dbname.mineralogy.classification is 'Classification system used to describe mineral: strunz, dana,...';
comment on column :dbname.mineralogy.formule is 'Chemical formulation';
comment on column :dbname.mineralogy.formule_indexed is 'Indexed form of foumule field';
comment on column :dbname.mineralogy.cristal_system is 'Cristal system defining the mineral structure: isometric, hexagonal,...';
comment on column :dbname.mineralogy.unit_class_ref is 'Reference of class the current unit depends of - id field of mineralogy table - recursive reference';
comment on column :dbname.mineralogy.unit_class_indexed is 'Indexed name of class the current unit depends of';
comment on column :dbname.mineralogy.unit_division_ref is 'Reference of division the current unit depends of - id field of mineralogy table - recursive reference';
comment on column :dbname.mineralogy.unit_division_indexed is 'Indexed name of division the current unit depends of';
comment on column :dbname.mineralogy.unit_family_ref is 'Reference of family the current unit depends of - id field of mineralogy table - recursive reference';
comment on column :dbname.mineralogy.unit_family_indexed is 'Indexed name of family the current unit depends of';
comment on column :dbname.mineralogy.unit_group_ref is 'Reference of group the current unit depends of - id field of mineralogy table - recursive reference';
comment on column :dbname.mineralogy.unit_group_indexed is 'Indexed name of group the current unit depends of';
comment on column :dbname.mineralogy.unit_variety_ref is 'Reference of sub level the current unit depends of - id field of mineralogy table - recursive reference';
comment on column :dbname.mineralogy.unit_variety_indexed is 'Indexed name of sub level the current unit depends of';
create table :dbname.lithology
       (
        constraint lithology_pk primary key (id),
        constraint lithology_unq unique (full_hierarchy_path),
        constraint lithology_catalogue_levels_fk foreign key (level_ref) references :dbname.catalogue_levels(id)
       )
inherits (:dbname.template_classifications);
comment on table :dbname.lithology is 'List of mineralogic units';
comment on column :dbname.lithology.id is 'Unique identifier of a classification unit';
comment on column :dbname.lithology.name is 'Classification unit name';
comment on column :dbname.lithology.name_indexed is 'Indexed form of name field';
comment on column :dbname.lithology.description_year is 'Year of description';
comment on column :dbname.lithology.description_year_compl is 'Complement to year of description: a, b, c, ...';
comment on column :dbname.lithology.level_ref is 'Reference of classification level the unit is encoded in';
comment on column :dbname.lithology.status is 'Validitiy status: valid, invalid, in discussion';
comment on column :dbname.lithology.full_hierarchy_path is 'Hierarchy path composed of parents ids and unit name - used for unique indexation';
comment on column :dbname.lithology.partial_hierarchy_path is 'Partial hierarchy path composed of non 0 parents ids -> reflexion of real hierarchy path for treeview ease of construction';
create table :dbname.habitats
       (
        id serial not null,
        code varchar not null,
        description varchar not null,
        description_ts tsvector not null,
        description_language_full_text full_text_language, 
        habitat_system varchar default 'eunis' not null,
        constraint habitats_pk primary key (id),
        constraint habitats_unq unique (code, habitat_system),
        constraint habitats_habitat_system_chk check (habitat_system in ('eunis', 'corine'))
       );
comment on table :dbname.habitats is 'Habitats classifications';
comment on column :dbname.habitats.id is 'Unique identifier of a habitat';
comment on column :dbname.habitats.code is 'Code given to this habitat in the classification encoded';
comment on column :dbname.habitats.description is 'General description of the habitat';
comment on column :dbname.habitats.description_ts is 'Indexed form of description field ready to be used with to_tsvector full text search function';
comment on column :dbname.habitats.description_language_full_text is 'Language used to compose the description_ts tsvector field';
comment on column :dbname.habitats.habitat_system is 'System used to describe habitat encoded';
create table :dbname.multimedia_keywords
       (
        object_ref integer not null,
        keyword varchar not null,
        keyword_indexed varchar not null,
        constraint multimedia_keywords_unq unique (object_ref, keyword_indexed),
        constraint multimedia_keywords_multimedia_fk foreign key (object_ref) references :dbname.multimedia(id)
       );
comment on table :dbname.multimedia_keywords is 'List of keywords associated to a multimedia object - encoded in the keywords field on the interface';
comment on column :dbname.multimedia_keywords.object_ref is 'Reference of multimedia object concerned';
comment on column :dbname.multimedia_keywords.keyword is 'Keyword associated';
comment on column :dbname.multimedia_keywords.keyword_indexed is 'Indexed form of keyword field';
create table :dbname.soortenregister
       (
        taxa_ref integer,
        gtu_ref integer,
        habitat_ref integer,
        date_from date,
        date_to date,
        constraint soortenregister_taxa_fk foreign key (taxa_ref) references :dbname.taxa(id),
        constraint soortenregister_gtu_fk foreign key (gtu_ref) references :dbname.gtu(id),
        constraint soortenregister_habitats_fk foreign key (habitat_ref) references :dbname.habitats(id)
       );
comment on table :dbname.soortenregister is 'Species register table - Indicates the presence of a certain species in a certain habitat at a given place from time to time';
comment on column :dbname.soortenregister.taxa_ref is 'Reference of taxon concerned - id field of taxa table';
comment on column :dbname.soortenregister.gtu_ref is 'Reference of gtu concerned - id field of gtu table';
comment on column :dbname.soortenregister.habitat_ref is 'Reference of habitat concerned - id field of habitats table';
comment on column :dbname.soortenregister.date_from is 'From date association definition';
comment on column :dbname.soortenregister.date_to is 'To date association definition';
create table :dbname.specimens
       (
        id serial not null,
        collection_ref integer not null,
        expedition_ref integer,
        sampling_ref integer not null,
        taxon_ref integer default 0 not null,
        litho_ref integer default 0 not null,
        chrono_ref integer default 0 not null,
        lithology_ref integer default 0 not null,
        mineral_ref integer default 0 not null,
        identification_qual varchar,
        identification_taxon_ref integer default 0 not null,
        host_taxon_ref integer default 0 not null,
        host_specimen_ref integer,
        host_relationship varchar,
        acquisition_category varchar default 'expedition' not null,
        acquisition_date_day date_day,
        acquisition_date_month date_month,
        acquisition_date_year date_year,
        collecting_method varchar,
        collecting_tool varchar,
        specimen_count_min integer default 1 not null,
        specimen_count_max integer default 1 not null,
        station_visible boolean default true not null,
        multimedia_visible boolean default true not null,
        category varchar default 'physical' not null,
        constraint specimens_pk primary key (id),
        constraint specimens_expeditions_fk foreign key (expedition_ref) references :dbname.expeditions(id),
        constraint specimens_gtu_fk foreign key (sampling_ref) references :dbname.gtu(id),
        constraint specimens_collections_fk foreign key (collection_ref) references :dbname.collections(id),
        constraint specimens_taxa_fk foreign key (taxon_ref) references :dbname.taxa(id),
        constraint specimens_lithostratigraphy_fk foreign key (litho_ref) references :dbname.lithostratigraphy(id),
        constraint specimens_lithology_fk foreign key (lithology_ref) references :dbname.lithology(id),
        constraint specimens_mineralogy_fk foreign key (mineral_ref) references :dbname.mineralogy(id),
        constraint specimens_chronostratigraphy_fk foreign key (chrono_ref) references :dbname.chronostratigraphy(id),
        constraint specimens_ident_taxa_fk foreign key (identification_taxon_ref) references :dbname.taxa(id),
        constraint specimens_host_taxa_fk foreign key (host_taxon_ref) references :dbname.taxa(id),
        constraint specimens_host_specimen_fk foreign key (host_specimen_ref) references :dbname.specimens(id),
        constraint specimens_acquisition_category_chk check (acquisition_category in ('expedition', 'donation', 'gift', 'loan', 'rent', 'buy', 'stolen')),
        constraint specimens_identification_qual_chk check (identification_qual in ('aff.', 'prox.', 'cf.', '?', 'incerteae sedis', 'sp.')),
        constraint specimens_category_chk check (category in ('physical', 'observation', 'figurate'))
       );
comment on table :dbname.specimens is 'Specimens or batch of specimens stored in collection';
comment on column :dbname.specimens.id is 'Unique identifier of a specimen or batch of specimens';
comment on column :dbname.specimens.collection_ref is 'Reference of collection the specimen is grouped under - id field of collections table';
comment on column :dbname.specimens.expedition_ref is 'When acquisition category is expedition, contains the reference of the expedition having conducted to the current specimen capture - id field of expeditions table';
comment on column :dbname.specimens.sampling_ref is 'Reference of the sampling location the specimen is coming from - id field of gtu table';
comment on column :dbname.specimens.litho_ref is 'When encoding a rock, mineral or paleontologic specimen, contains the reference of lithostratigraphic unit the specimen have been found into - id field of lithostratigraphy table';
comment on column :dbname.specimens.chrono_ref is 'When encoding a rock, mineral or paleontologic specimen, contains the reference of chronostratigraphic unit the specimen have been found into - id field of chronostratigraphy table';
comment on column :dbname.specimens.taxon_ref is 'When encoding a ''living'' specimen, contains the reference of the taxon unit defining the specimen - id field of taxa table';
comment on column :dbname.specimens.identification_qual is 'Qualifier of taxonomic definition: sp., prox. aff., cf., ...';
comment on column :dbname.specimens.identification_taxon_ref is 'When taxonomic qualifier specified - can contain the reference of the taxon the qualifier targetsi - id field of taxa table';
comment on column :dbname.specimens.host_relationship is 'When current specimen encoded is in a host relationship with an other specimen or taxon, this field contains the type of relationship between them: symbiosis, parasitism, saprophytism,...';
comment on column :dbname.specimens.host_specimen_ref is 'When current specimen encoded is in a host relationship with an other specimen, this field contains reference of the host specimen - recursive reference';
comment on column :dbname.specimens.acquisition_category is 'Describe how the specimen was collected: expedition, donation,...';
comment on column :dbname.specimens.acquisition_date_day is 'Day of specimen acquisition';
comment on column :dbname.specimens.acquisition_date_month is 'Month of specimen acquisition';
comment on column :dbname.specimens.acquisition_date_year is 'Year of specimen acquisition';
comment on column :dbname.specimens.collecting_method is 'Collecting method used to collect the specimen';
comment on column :dbname.specimens.collecting_tool is 'Collecting tool used to collect the specimen';
comment on column :dbname.specimens.specimen_count_min is 'Minimum number of individuals in batch';
comment on column :dbname.specimens.specimen_count_max is 'Maximum number of individuals in batch';
comment on column :dbname.specimens.multimedia_visible is 'Flag telling if the multimedia attached to this specimen can be visible or not';
comment on column :dbname.specimens.station_visible is 'Flag telling if the sampling location can be visible or must be hidden for the specimen encoded';
comment on column :dbname.specimens.category is 'Type of specimen encoded: a physical object stored in collections, an observation, a figurate specimen,...';
create table :dbname.template_specimen_codes
       (
        code_category varchar default 'main' not null,
        code_prefix varchar,
        code integer,
        code_suffix varchar,
        full_code_indexed varchar not null,
        code_date timestamp,
        constraint template_specimen_codes_chk check (code_category in ('main', 'secondary', 'temporary', 'mn/mp', 'ig', 'db'))
       );
comment on table :dbname.template_specimen_codes is 'Template used to construct the specimen codes tables';
comment on column :dbname.template_specimen_codes.code_category is 'Category of code: main, secondary, temporary,...';
comment on column :dbname.template_specimen_codes.code_prefix is 'Code prefix - entire code if all alpha, begining character part if code is made of characters and numeric parts';
comment on column :dbname.template_specimen_codes.code is 'Numerical part of code';
comment on column :dbname.template_specimen_codes.code_suffix is 'For codes made of characters and numerical parts, this field stores the last alpha part of code';
comment on column :dbname.template_specimen_codes.full_code_indexed is 'Full code composition by code_prefix, code and code suffix concatenation and indexed for unique check purpose';
comment on column :dbname.template_specimen_codes.code_date is 'Date of code creation';
create table :dbname.specimens_codes
       (
        specimen_ref integer not null,
        constraint specimens_codes_unq unique (specimen_ref, code_category, full_code_indexed),
        constraint specimens_codes_specimens_fk foreign key (specimen_ref) references :dbname.specimens(id)
       )
inherits (:dbname.template_specimen_codes);
comment on table :dbname.specimens_codes is 'List of codes associated to a specimen';
comment on column :dbname.specimens_codes.specimen_ref is 'Reference of specimen concerned - id field of specimens table';
comment on column :dbname.specimens_codes.code_category is 'Category of code: main, secondary, temporary,...';
comment on column :dbname.specimens_codes.code_prefix is 'Code prefix - entire code if all alpha, begining character part if code is made of characters and numeric parts';
comment on column :dbname.specimens_codes.code is 'Numerical part of code';
comment on column :dbname.specimens_codes.code_suffix is 'For codes made of characters and numerical parts, this field stores the last alpha part of code';
comment on column :dbname.specimens_codes.full_code_indexed is 'Full code composition by code_prefix, code and code suffix concatenation and indexed for unique check purpose';
comment on column :dbname.specimens_codes.code_date is 'Date of code creation';
create table :dbname.specimen_individuals
       (
        id serial not null,
        specimen_ref integer not null,
        type varchar default 'specimen' not null,
        type_group varchar,
        type_search varchar,
        sex varchar default 'undefined' not null,
        stage varchar default 'undefined' not null,
        state varchar default 'not applicable' not null,
        social_status varchar default 'not applicable' not null,
        rock_form varchar default 'not applicable' not null,
        specimen_individuals_count_min integer default 1 not null,
        specimen_individuals_count_max integer default 1 not null,
        constraint specimen_individuals_pk primary key (id),
        constraint specimen_individuals_unq unique (specimen_ref, type, sex, stage, state, social_status, rock_form),
        constraint specimen_individuals_specimens_fk foreign key (specimen_ref) references :dbname.specimens(id)
       );
comment on table :dbname.specimen_individuals is 'Stores characterized individudals from a specimen batch';
comment on column :dbname.specimen_individuals.id is 'Unique identifier of a specimen individual';
comment on column :dbname.specimen_individuals.specimen_ref is 'Reference of a specimen batch the individual(s) is/are extracted from';
comment on column :dbname.specimen_individuals.type is 'Special status given to individual(s): holotype, paratype,...';
comment on column :dbname.specimen_individuals.type_group is 'For some special status, a common appelation is used - ie: topotype and cotype are joined into a common appelation of syntype';
comment on column :dbname.specimen_individuals.type_search is 'On the interface, the separation in all special status is not suggested for non official appelations. For instance, an unified grouping name is provided: type for non official appelation,...';
comment on column :dbname.specimen_individuals.sex is 'Individual sex: male , female,...';
comment on column :dbname.specimen_individuals.stage is 'Individual stage: adult, juvenile,...';
comment on column :dbname.specimen_individuals.state is 'Individual state - a sex complement: ovigerous, pregnant,...';
comment on column :dbname.specimen_individuals.social_status is 'For social specimens, give the social status/role of individual in colony';
comment on column :dbname.specimen_individuals.rock_form is 'For rock specimens/individuals, a descriptive form can be given: polygonous,...';
comment on column :dbname.specimen_individuals.specimen_individuals_count_min is 'Minimum number of individuals';
comment on column :dbname.specimen_individuals.specimen_individuals_count_max is 'Maximum number of individuals';
create table :dbname.specimen_parts
       (
        id serial not null,
        specimen_individual_ref integer not null,
        specimen_part varchar default 'specimen' not null,
        building varchar,
        floor varchar,
        room varchar,
        row varchar, 
        shelf varchar,
        container varchar,
        sub_container varchar,
        container_type varchar,
        sub_container_type varchar,
        storage varchar default 'dry' not null,
        surnumerary boolean default false not null,
        specimen_status varchar default 'good state' not null,
        specimen_part_count_min integer default 1 not null,
        specimen_part_count_max integer default 1 not null,
        constraint specimen_parts_pk primary key (id),
        constraint specimen_parts_specimen_individuals foreign key (specimen_individual_ref) references :dbname.specimen_individuals(id)
       );
comment on table :dbname.specimen_parts is 'List of individuals or parts of individuals stored in conservatories';
comment on column :dbname.specimen_parts.id is 'Unique identifier of a specimen part/individual';
comment on column :dbname.specimen_parts.specimen_individual_ref is 'Reference of corresponding characterized specimen';
comment on column :dbname.specimen_parts.specimen_part is 'Description of the part stored in conservatory: the whole specimen or a given precise part such as skelleton, head, fur,...';
comment on column :dbname.specimen_parts.building is 'Building the part/individual is stored in';
comment on column :dbname.specimen_parts.floor is 'Floor the part/individual is stored in';
comment on column :dbname.specimen_parts.room is 'Room the part/individual is stored in';
comment on column :dbname.specimen_parts.row is 'Row the part/individual is stored in';
comment on column :dbname.specimen_parts.shelf is 'Shelf the part/individual is stored in';
comment on column :dbname.specimen_parts.container is 'Container the part/individual is stored in';
comment on column :dbname.specimen_parts.sub_container is 'Sub-Container the part/individual is stored in';
comment on column :dbname.specimen_parts.container_type is 'Type of container: box, plateau-caisse,...';
comment on column :dbname.specimen_parts.sub_container_type is 'Type of sub-container: slide, needle,...';
comment on column :dbname.specimen_parts.storage is 'Conservative medium used: formol, alcohool, dry,...';
comment on column :dbname.specimen_parts.surnumerary is 'Tells if this part/individual has been added after first inventory';
comment on column :dbname.specimen_parts.specimen_status is 'Specimen status: good state, lost, damaged,...';
comment on column :dbname.specimen_parts.specimen_part_count_min is 'Minimum number of parts/individuals';
comment on column :dbname.specimen_parts.specimen_part_count_max is 'Maximum number of parts/individuals';
create table :dbname.specimen_parts_codes
       (
        specimen_part_ref integer not null,
        constraint specimen_parts_codes_unq unique (specimen_part_ref, code_category, full_code_indexed),
        constraint specimen_parts_codes_specimen_parts_fk foreign key (specimen_part_ref) references :dbname.specimen_parts(id)
       )
inherits (:dbname.template_specimen_codes);
comment on table :dbname.specimen_parts_codes is 'List of codes given to specimen parts/individuals';
comment on column :dbname.specimen_parts_codes.specimen_part_ref is 'Reference of specimen part concerned - id field of specimen_parts table';
comment on column :dbname.specimen_parts_codes.code_category is 'Category of code: main, secondary, temporary,...';
comment on column :dbname.specimen_parts_codes.code_prefix is 'Code prefix - entire code if all alpha, begining character part if code is made of characters and numeric parts';
comment on column :dbname.specimen_parts_codes.code is 'Numerical part of code';
comment on column :dbname.specimen_parts_codes.code_suffix is 'For codes made of characters and numerical parts, this field stores the last alpha part of code';
comment on column :dbname.specimen_parts_codes.full_code_indexed is 'Full code composition by code_prefix, code and code suffix concatenation and indexed for unique check purpose';
comment on column :dbname.specimen_parts_codes.code_date is 'Date of code creation';
create table :dbname.specimen_parts_insurances
       (
        specimen_part_ref integer not null,
        insurance_year smallint default extract(year from now()) not null,
        insurance_value numeric default 0 not null,
        insurer_ref integer,
        constraint specimen_parts_insurances_unq unique (specimen_part_ref, insurance_year),
        constraint specimen_parts_insurances_specimen_parts_fk foreign key (specimen_part_ref) references :dbname.specimen_parts(id),
        constraint specimen_parts_insurances_people_fk foreign key (insurer_ref) references :dbname.people(id)
       );
comment on table :dbname.specimen_parts_insurances is 'List of insurances values for given specimen parts/individuals';
comment on column :dbname.specimen_parts_insurances.specimen_part_ref is 'Reference of specimen part/individual concerned - id field of specimen_parts table';
comment on column :dbname.specimen_parts_insurances.insurance_year is 'Reference year for insurance subscription';
comment on column :dbname.specimen_parts_insurances.insurance_value is 'Insurance value';
comment on column :dbname.specimen_parts_insurances.insurer_ref is 'Reference of the insurance firm an insurance have been subscripted at';
create table :dbname.associated_multimedia
       (
        table_ref integer not null,
        record_id integer not null,
        multimedia_ref integer not null,
        constraint associated_multimedia_unq unique (multimedia_ref, table_ref, record_id),
        constraint associated_multimedia_multimedia_fk foreign key (multimedia_ref) references :dbname.multimedia(id),
        constraint associated_multimedia_table_list_fk foreign key (table_ref) references :dbname.table_list(id)
       );
comment on table :dbname.associated_multimedia is 'List of all associated multimedia to an element of DaRWIN 2 application: specimen, catalogue unit';
comment on column :dbname.associated_multimedia.table_ref is 'Reference of table concerned - id field of table_list table';
comment on column :dbname.associated_multimedia.record_id is 'Identifier of record concerned';
comment on column :dbname.associated_multimedia.multimedia_ref is 'Reference of multimedia object concerned - id field of multimedia table';
create table :dbname.specimen_accompanying_minerals
       (
        specimen_ref integer not null,
        mineral_ref integer not null,
        type varchar default 'secondary' not null,
        quantity real,
        unit varchar default '%' not null,
        defined_by_ordered_ids_list integer[],
        remarks text,
        remarks_ts tsvector,
        constraint specimen_accompanying_minerals_unq unique (specimen_ref, mineral_ref),
        constraint specimen_accompanying_minerals_specimens_fk foreign key (specimen_ref) references :dbname.specimens(id),
        constraint specimen_accompanying_minerals_mineralogy_fk foreign key (mineral_ref) references :dbname.mineralogy(id),
        constraint specimen_accompanying_minerals_type_chk check (type in ('main', 'secondary', 'trace'))
       );
comment on table :dbname.specimen_accompanying_minerals is 'For rock or minerals specimens, will list all the accompanying minerals found';
comment on column :dbname.specimen_accompanying_minerals.specimen_ref is 'Reference of specimen concerned - id field of specimens table';
comment on column :dbname.specimen_accompanying_minerals.mineral_ref is 'Reference of accompanying mineral - id field of mineralogy table';
comment on column :dbname.specimen_accompanying_minerals.type is 'Type of mineral: main or secondary';
comment on column :dbname.specimen_accompanying_minerals.quantity is 'Quantity of mineral';
comment on column :dbname.specimen_accompanying_minerals.unit is 'Unit used for quantity of mineral presence';
comment on column :dbname.specimen_accompanying_minerals.defined_by_ordered_ids_list is 'Array of persons ids having defined these accompanying minerals';
comment on column :dbname.specimen_accompanying_minerals.remarks is 'Descriptive remarks';
comment on column :dbname.specimen_accompanying_minerals.remarks_ts is 'tsvector form of remarks field - used for full text search with to_tsvector function';
create table :dbname.fossiles
       (
        specimen_ref integer not null,
        taxon_ref integer not null,
        type varchar default 'secondary' not null,
        form varchar,
        quantity real,
        unit varchar default '%' not null,
        defined_by_ordered_ids_list integer[],
        remarks text,
        remarks_ts tsvector,
        constraint fossiles_unq unique (specimen_ref, taxon_ref),
        constraint fossiles_specimens_fk foreign key (specimen_ref) references :dbname.specimens(id),
        constraint fossiles_taxa_fk foreign key (taxon_ref) references :dbname.taxa(id),
        constraint fossiles_type_chk check (type in ('main', 'secondary', 'trace'))
       );
comment on table :dbname.fossiles is 'For paleontological specimens, will list all the accompanying taxa found';
comment on column :dbname.fossiles.specimen_ref is 'Reference of specimen concerned - id field of specimens table';
comment on column :dbname.fossiles.taxon_ref is 'Reference of accompanying taxa - id field of taxa table';
comment on column :dbname.fossiles.type is 'Type of taxon: main or secondary';
comment on column :dbname.fossiles.form is 'Form of accompanying fossile/taxon in the rock: aggregates, circles, colony,...';
comment on column :dbname.fossiles.quantity is 'Quantity of taxon';
comment on column :dbname.fossiles.unit is 'Unit used for quantity of taxon presence';
comment on column :dbname.fossiles.defined_by_ordered_ids_list is 'Array of persons ids having defined these accompanying taxa';
comment on column :dbname.fossiles.remarks is 'Descriptive remarks';
comment on column :dbname.fossiles.remarks_ts is 'tsvector form of remarks field - used for full text search with to_tsvector function';

