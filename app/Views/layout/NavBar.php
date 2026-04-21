<?php
$session = session();
$user = $session->get('usuario') ?? [];

$roles = $user['roles'] ?? [];
if (!is_array($roles))
  $roles = [$roles];
$roles = array_map(fn($r) => strtoupper(trim((string) $r)), $roles);

$can = function (array $allowedRoles = []) use ($roles): bool {
  if (empty($allowedRoles))
    return true;
  $allowedRoles = array_map(fn($r) => strtoupper(trim((string) $r)), $allowedRoles);
  return count(array_intersect($roles, $allowedRoles)) > 0;
};
?>

<?= $this->section('navbar') ?>


<?= $this->endSection() ?>