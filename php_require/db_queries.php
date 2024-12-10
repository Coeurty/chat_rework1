<?php
function getFriendRequestNumber(PDO $bdd, $userId)
{
    $request = $bdd->prepare(
        'SELECT COUNT(*) AS unaccepted_requests 
                FROM relations 
                WHERE receiver = :user_id AND is_accepted = 0'
    );
    $request->execute(['user_id' => $userId]);
    $friendRequestNumber = $request->fetch();
    return $friendRequestNumber[0];
}

function isFriendByPseudo(PDO $bdd, $userId, $pseudo): bool
{
    $findAccountIdByPseudo = $bdd->prepare(
        "SELECT user_id FROM accounts WHERE user_pseudo = :pseudo"
    );
    $findAccountIdByPseudo->execute(["pseudo" => $pseudo,]);
    $foundAccount = $findAccountIdByPseudo->fetch();

    $findRelation = $bdd->prepare(
        "SELECT *
            FROM relations
            WHERE (
                 (requester = :user1_id AND receiver = :user2_id)
                OR (requester = :user2_id AND receiver = :user1_id)
               );"
    );
    $findRelation->execute(["user1_id" => $userId, "user2_id" => $foundAccount["user_id"]]);
    return $findRelation->fetch() !== false;
}

function removeFriend(PDO $bdd, $userId, $pseudo)
{
    $findAccountIdByPseudo = $bdd->prepare(
        "SELECT user_id FROM accounts WHERE user_pseudo = :pseudo"
    );
    $findAccountIdByPseudo->execute(["pseudo" => $pseudo,]);
    $foundAccount = $findAccountIdByPseudo->fetch();

    $deleteRelation = $bdd->prepare(
        "DELETE FROM relations
                WHERE (
                     (requester = :user1_id AND receiver = :user2_id)
                    OR (requester = :user2_id AND receiver = :user1_id)
                );"
    );
    $deleteRelation->execute(["user1_id" => $userId, "user2_id" => $foundAccount["user_id"]]);
}

function createFriendRequest(PDO $bdd, $userId, $pseudo)
{
    $findAccountByPseudo = $bdd->prepare('SELECT user_id FROM accounts WHERE user_pseudo = :pseudo');
    $findAccountByPseudo->execute(['pseudo' => $pseudo]);
    $foundAccount = $findAccountByPseudo->fetch();

    // TODO: empêcher les demandes d'amis vers soi-même et les doublons
    $createRelation = $bdd->prepare('INSERT INTO relations(requester, receiver) VALUES(:requester_id, :receiver_id)');
    $createRelation->execute(
        array(
            'requester_id' => $userId,
            'receiver_id' => $foundAccount['user_id']
        ),
    );
}