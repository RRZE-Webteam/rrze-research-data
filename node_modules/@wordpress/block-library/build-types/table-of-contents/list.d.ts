/**
 * External dependencies
 */
import type { MouseEvent, ReactElement } from 'react';
/**
 * Internal dependencies
 */
import type { NestedHeadingData } from './utils';
export default function TableOfContentsList({ nestedHeadingList, disableLinkActivation, onClick, ordered, }: {
    nestedHeadingList: NestedHeadingData[];
    disableLinkActivation?: boolean;
    onClick?: (event: MouseEvent<HTMLAnchorElement>) => void;
    ordered?: boolean;
}): ReactElement;
//# sourceMappingURL=list.d.ts.map