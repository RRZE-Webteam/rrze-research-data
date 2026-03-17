/**
 * Calculates the Shortest Common Supersequence (SCS) of two sequences.
 *
 * A supersequence is a sequence that contains both input sequences as subsequences.
 * The shortest common supersequence is the shortest possible such sequence.
 *
 * This implementation uses dynamic programming with a time complexity of O(mn)
 * and space complexity of O(mn), where m and n are the lengths of sequences X and Y.
 *
 * @example
 * ```ts
 * const seq1 = [1, 3, 5];
 * const seq2 = [2, 3, 4];
 * const scs = shortestCommonSupersequence(seq1, seq2); // [1, 2, 3, 4, 5]
 * ```
 *
 * @param X       The first sequence.
 * @param Y       The second sequence.
 * @param isEqual Optional equality function to compare elements.
 *                Defaults to strict equality (===).
 * @return The shortest common supersequence of X and Y.
 */
export declare function shortestCommonSupersequence<E = unknown>(X: E[], Y: E[], isEqual?: (a: E, b: E) => boolean): E[];
//# sourceMappingURL=scs.d.ts.map