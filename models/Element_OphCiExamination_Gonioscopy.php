<?php

/**
 * OpenEyes
 *
 * (C) University of Cardiff, 2012
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) University of Cardiff, 2012
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

/**
 * This is the model class for table "et_ophciexamination_gonioscopy".
 *
 * The followings are the available columns in table 'et_ophciexamination_gonioscopy':
 * @property integer $id
 * @property integer $event_id
 * @property string $left_description
 * @property string $right_description
 * @property OphCiExamination_Gonioscopy_Description $left_gonio
 * @property OphCiExamination_Gonioscopy_Description $right_gonio
 * @property OphCiExamination_Gonioscopy_Van_Herick $left_van_herick
 * @property OphCiExamination_Gonioscopy_Van_Herick $right_van_herick
 * @property string $left_eyedraw
 * @property string $right_eyedraw
 *
 * The followings are the available model relations:
 * @property Event $event
 */
class Element_OphCiExamination_Gonioscopy extends BaseEventTypeElement {

	/**
	 * Returns the static model of the specified AR class.
	 * @return the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'et_ophciexamination_gonioscopy';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('id, event_id, left_gonio_id, right_gonio_id, left_van_herick_id, right_van_herick_id,
						left_description, right_description, left_eyedraw, right_eyedraw', 'safe'),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, event_id, left_description, right_description, left_eyedraw, right_eyedraw',
						'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'element_type' => array(self::HAS_ONE, 'ElementType', 'id','on' => "element_type.class_name='".get_class($this)."'"),
				'eventType' => array(self::BELONGS_TO, 'EventType', 'event_type_id'),
				'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
				'user' => array(self::BELONGS_TO, 'User', 'created_user_id'),
				'usermodified' => array(self::BELONGS_TO, 'User', 'last_modified_user_id'),
				'left_gonio' => array(self::BELONGS_TO, 'OphCiExamination_Gonioscopy_Description', 'left_gonio_id'),
				'right_gonio' => array(self::BELONGS_TO, 'OphCiExamination_Gonioscopy_Description', 'right_gonio_id'),
				'left_van_herick' => array(self::BELONGS_TO, 'OphCiExamination_Gonioscopy_Van_Herick', 'left_van_herick_id'),
				'right_van_herick' => array(self::BELONGS_TO, 'OphCiExamination_Gonioscopy_Van_Herick', 'right_van_herick_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
				'id' => 'ID',
				'event_id' => 'Event',
				'left_van_herick_id' => 'Van Herick',
				'right_van_herick_id' => 'Van Herick',
				'left_gonio_id' => 'Gonioscopy',
				'right_gonio_id' => 'Gonioscopy',
				'left_description' => 'Description',
				'right_description' => 'Description',
				'left_eyedraw' => 'EyeDraw',
				'right_eyedraw' => 'EyeDraw'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('event_id', $this->event_id, true);
		$criteria->compare('left_gonio_id', $this->left_gonio_id, true);
		$criteria->compare('right_gonio_id', $this->right_gonio_id, true);
		$criteria->compare('left_van_herick_id', $this->left_van_herick_id, true);
		$criteria->compare('right_van_herick_id', $this->right_van_herick_id, true);
		$criteria->compare('left_description', $this->left_description, true);
		$criteria->compare('right_description', $this->right_description, true);
		$criteria->compare('left_eyedraw', $this->left_eyedraw, true);
		$criteria->compare('right_eyedraw', $this->right_eyedraw, true);

		return new CActiveDataProvider(get_class($this), array(
				'criteria' => $criteria,
		));
	}

	/**
	 *
	 * @return array
	 */
	function getGonioscopyOptions() {
		return CHtml::listData(OphCiExamination_Gonioscopy_Description::model()
				->findAll(array('order'=>'display_order')),'id','name');
	}

	/**
	 *
	 * @return array
	 */
	function getVanHerickOptions() {
		return array(0 => 'NR') + CHtml::listData(OphCiExamination_Gonioscopy_Van_Herick::model()
				->findAll(array('order'=>'display_order')),'id','name');
	}

	/**
	 * Set default values for forms on create
	 */
	public function setDefaultOptions() {
	}

	protected function beforeSave() {
		return parent::beforeSave();
	}

	protected function afterSave() {
		return parent::afterSave();
	}

	protected function beforeValidate() {
		return parent::beforeValidate();
	}

}
