<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */
?>
<div class="element-data element-eyes row">
	<div class="element-eye right-eye column">
		<div class="data-row">
			<div class="data-value">
				<?php if ($element->hasRight()) {
    ?>
					<table cellspacing="0">
						<thead>
						<tr>
							<th width="25%">Area (Central)</th>
							<th width="25%">Area (Maximal)</th>
							<th width="25%">Height</th>
							<th width="25%">Vascularity</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td style="text-align: center;"><?php echo $element->right_central_area->area;
    ?></td>
							<td style="text-align: center;"><?php echo $element->right_max_area->area;
    ?></td>
							<td style="text-align: center;"><?php echo $element->right_height->height;
    ?></td>
							<td style="text-align: center;"><?php echo $element->right_vasc->vascularity;
    ?></td>
						</tr>
						</tbody>
					</table>
					<?php

} else {
    ?>
					Not recorded
				<?php 
}?>
			</div>
		</div>
	</div>
	<div class="element-eye left-eye column">
		<div class="data-row">
			<div class="data-value">
				<?php if ($element->hasLeft()) {
    ?>
					<table cellspacing="0">
						<thead>
						<tr>
							<th width="25%">Area (Central)</th>
							<th width="25%">Area (Maximal)</th>
							<th width="25%">Height</th>
							<th width="25%">Vascularity</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td style="text-align: center;"><?php echo $element->left_central_area->area;
    ?></td>
							<td style="text-align: center;"><?php echo $element->left_max_area->area;
    ?></td>
							<td style="text-align: center;"><?php echo $element->left_height->height;
    ?></td>
							<td style="text-align: center;"><?php echo $element->left_vasc->vascularity;
    ?></td>
						</tr>
						</tbody>
					</table>
					<?php

} else {
    ?>
					Not recorded
				<?php 
}?>
			</div>
		</div>
	</div>
</div>
