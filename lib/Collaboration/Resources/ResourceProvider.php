<?php
/**
 * @copyright Copyright (c) 2019 Julius Härtl <jus@bitgrid.net>
 *
 * @author Julius Härtl <jus@bitgrid.net>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Files\Collaboration\Resources;

use OCP\Collaboration\Resources\IProvider;
use OCP\Collaboration\Resources\IResource;
use OCP\Files\IRootFolder;
use OCP\Files\Node;
use OCP\IURLGenerator;
use OCP\IUser;

class ResourceProvider implements IProvider {

	const RESOURCE_TYPE = 'deck_board';

	/** @var BoardMapper */
	private $boardMapper;

	/** @var IURLGenerator */
	private $urlGenerator;

	/** @var array */
	protected $boards = [];

	public function __construct(BoardMapper $boardMapper, IURLGenerator $urlGenerator) {
		$this->boardMapper = $boardMapper;
		$this->urlGenerator = $urlGenerator;
	}

	private function getBoard(IResource $resource): ?Board {
		if (isset($this->boards[(int) $resource->getId()])) {
			return $this->boards[(int) $resource->getId()];
		}
		$boards = $this->boardMapper->get((int) $resource->getId());
	}

	/**
	 * Get the display name of a resource
	 *
	 * @param IResource $resource
	 * @return string
	 * @since 15.0.0
	 */
	public function getName(IResource $resource): string {
		$board = $this->getBoard($resource);
		if ($board !== null) {
			return $board->getTitle();
		}
		return '';
	}

	/**
	 * Can a user/guest access the collection
	 *
	 * @param IResource $resource
	 * @param IUser $user
	 * @return bool
	 * @since 15.0.0
	 */
	public function canAccessResource(IResource $resource, IUser $user = null): bool {
		if (!$user instanceof IUser) {
			return false;
		}

		$board = $this->getBoard($resource);

		return false;
	}

	/**
	 * Get the icon class of a resource
	 *
	 * @param IResource $resource
	 * @return string
	 * @since 15.0.0
	 */
	public function getIconClass(IResource $resource): string {
		return 'icon-deck';
	}

	/**
	 * Get the resource type of the provider
	 *
	 * @return string
	 * @since 15.0.0
	 */
	public function getType(): string {
		return self::RESOURCE_TYPE;
	}

	/**
	 * Get the link to a resource
	 *
	 * @param IResource $resource
	 * @return string
	 * @since 15.0.0
	 */
	public function getLink(IResource $resource): string {
		return $this->urlGenerator->linkToRoute('deck.pagecontroller.index') . '#!/boards/' . $resource->getId();
	}
}
